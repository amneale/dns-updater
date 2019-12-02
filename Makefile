#####################
# V A R I A B L E S #
#####################

COLOR_RESET   = \033[0m
COLOR_INFO    = \033[32m
COLOR_COMMENT = \033[33m

#################
# T A R G E T S #
#################

default: help
.PHONY: help

help: ## Display this help message
	@printf "${COLOR_COMMENT}Usage:${COLOR_RESET}\n"
	@printf " make [target]\n\n"
	@printf "${COLOR_COMMENT}Available targets:${COLOR_RESET}\n"
	@grep -E '^[a-zA-Z_-]+:.*?## .*$$' $(MAKEFILE_LIST) | awk 'BEGIN {FS = ":.*?## "}; { \
		printf " ${COLOR_INFO}%-30s${COLOR_RESET} %s\n", $$1, $$2 \
	}'

vendor: composer.json composer.lock
	composer install

.PHONY: test code-style ci

test: vendor ## Run test suite
	./vendor/bin/phpspec run -fpretty
	./vendor/bin/behat

code-style: vendor ## Analyse code style
	./vendor/bin/php-cs-fixer fix

ci: vendor ## Run CI tests and exit if defect found
	./vendor/bin/php-cs-fixer fix -v --dry-run
	./vendor/bin/phpspec run -fdot
	./vendor/bin/behat --format=progress
