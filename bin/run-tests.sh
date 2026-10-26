#!/usr/bin/env bash
# =============================================================================
#  Lance tous les tests du projet Hôpital de la Main et affiche un bilan.
#
#  Utilisation :
#     bin/run-tests.sh                 -> tout lancer
#     bin/run-tests.sh unitaire        -> une seule famille
#     bin/run-tests.sh securite erreurs
#
#  Familles : erreurs, unitaire, integration, securite, orthographe
#  Code de sortie : 0 si tout passe, 1 sinon (utilisable en intégration continue).
# =============================================================================

set -u
cd "$(dirname "$0")/.." || exit 1

VERT=$'\e[32m'; ROUGE=$'\e[31m'; JAUNE=$'\e[33m'; GRAS=$'\e[1m'; FIN=$'\e[0m'
RESULTATS=()
ECHECS=0

titre()  { printf '\n%s==== %s ====%s\n' "$GRAS" "$1" "$FIN"; }

# Exécute une commande, affiche sa sortie seulement en cas d'échec, et note le résultat.
etape() {
    local nom="$1"; shift
    local sortie
    if sortie=$("$@" 2>&1); then
        printf '  %s✔%s %s\n' "$VERT" "$FIN" "$nom"
        RESULTATS+=("${VERT}✔${FIN} $nom")
    else
        printf '  %s✘ %s%s\n' "$ROUGE" "$nom" "$FIN"
        printf '%s\n' "$sortie" | tail -40 | sed 's/^/      /'
        RESULTATS+=("${ROUGE}✘${FIN} $nom")
        ECHECS=$((ECHECS + 1))
    fi
}

avertissement() {
    printf '  %s⚠ %s%s\n' "$JAUNE" "$1" "$FIN"
    RESULTATS+=("${JAUNE}⚠${FIN} $1")
}

phpunit_suite() { vendor/bin/phpunit --testsuite "$1" --colors=never; }

syntaxe_php() {
    local erreur=0
    while IFS= read -r -d '' f; do
        php -l "$f" >/dev/null 2>&1 || { php -l "$f"; erreur=1; }
    done < <(find src tests migrations -name '*.php' -print0)
    return $erreur
}

# ---------------------------------------------------------------------------
tests_erreurs() {
    titre "1. Détection d'erreurs (analyse statique + tests)"
    etape "Syntaxe PHP de tous les fichiers"         syntaxe_php
    etape "Gabarits Twig valides"                    php bin/console lint:twig templates --env=test
    etape "Fichiers YAML valides"                    php bin/console lint:yaml config --parse-tags --env=test
    etape "Services du conteneur correctement typés" php bin/console lint:container --env=test
    etape "Mapping Doctrine valide"                  php bin/console doctrine:schema:validate --skip-sync --env=test
    etape "Tests d'erreurs (404, 405, 500, rôles)"   phpunit_suite erreurs
}

tests_unitaire()    { titre "2. Tests unitaires";       etape "Tests unitaires (entités, services, sécurité)" phpunit_suite unitaire; }
tests_integration() { titre "3. Tests d'intégration";   etape "Tests d'intégration (base de données, parcours)" phpunit_suite integration; }

tests_securite() {
    titre "4. Tests de sécurité"
    etape "Contrôle d'accès, CSRF, envoi de fichiers" phpunit_suite securite
    etape "Dépendances sans faille connue (composer audit)" composer audit --no-interaction
    if git ls-files --error-unmatch .env.local >/dev/null 2>&1; then
        avertissement ".env.local est versionné dans git : il peut contenir des mots de passe"
    fi
    if [ -n "$(git ls-files public/uploads)" ]; then
        avertissement "Des images patients sont versionnées dans git (git rm -r --cached public/uploads)"
    fi
}

tests_orthographe() {
    titre "5. Tests d'orthographe"
    etape "Fautes connues et encodage UTF-8" phpunit_suite orthographe
    if command -v aspell >/dev/null 2>&1 && aspell dicts 2>/dev/null | grep -q '^fr'; then
        local inconnus
        inconnus=$(cat templates/*/*.twig | sed -E 's/\{[{%#][^}]*[}%#]\}//g; s/<[^>]*>//g' \
                   | aspell list --lang=fr --encoding=utf-8 | sort -u | head -30 | tr '\n' ' ')
        [ -n "$inconnus" ] && avertissement "Mots inconnus d'aspell, à relire : $inconnus"
    else
        printf '  (Astuce : "brew install aspell" ajoute une vérification par dictionnaire français)\n'
    fi
}

# ---------------------------------------------------------------------------
FAMILLES=("$@")
[ ${#FAMILLES[@]} -eq 0 ] && FAMILLES=(erreurs unitaire integration securite orthographe)

for famille in "${FAMILLES[@]}"; do
    case "$famille" in
        erreurs|unitaire|integration|securite|orthographe) "tests_$famille" ;;
        *) printf '%sFamille inconnue : %s%s\n' "$ROUGE" "$famille" "$FIN"; exit 2 ;;
    esac
done

titre "BILAN"
printf '  %s\n' "${RESULTATS[@]}"
if [ "$ECHECS" -eq 0 ]; then
    printf '\n%s%sTout est vert ✔%s\n' "$GRAS" "$VERT" "$FIN"
else
    printf '\n%s%s%d vérification(s) en échec ✘%s\n' "$GRAS" "$ROUGE" "$ECHECS" "$FIN"
fi
exit $((ECHECS > 0))
