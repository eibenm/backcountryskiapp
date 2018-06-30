.DEFAULT_GOAL := help

# Cite: https://marmelab.com/blog/2016/02/29/auto-documented-makefile.html
.PHONY: help
help:
	@grep -E '^[a-zA-Z_-]+:.*?## .*$$' $(MAKEFILE_LIST) | awk 'BEGIN {FS = ":.*?## "}; {printf "\033[36m%-30s\033[0m %s\n", $$1, $$2}'

.PHONY: up
up: ## Run the application.
	@docker-compose up -d
	@echo "Application availible: http://localhost:80/"

.PHONY: down
down: ## Stop the applicaiton.
	@docker-compose down -v