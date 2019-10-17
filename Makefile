# Environment variables
ENV_FILE = ./.env
include $(ENV_FILE)

# Commands
test:
	echo "Hexo blog"

install:
	npm install

generate:
	hexo clean
	hexo generate
generate_watch:
	hexo clean
	hexo generate --watch

dev_watch:
	hexo clean
	hexo server -i 0.0.0.0 -p 4000 --draft
dev_serve_public:
	hexo server -s

deploy:
	hexo clean
	hexo generate
	echo $(CNAME) >> docs/CNAME
	hexo deploy
