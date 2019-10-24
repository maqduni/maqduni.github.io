# Environment variables
ENV_FILE = ./.env
include $(ENV_FILE)

# Commands
test:
	echo "Hexo blog"

install:
	npm install

watch:
	hexo clean
	hexo server -i 0.0.0.0 -p 4000 --draft

generate:
	hexo clean
	mkdir ./docs
	rsync -av ./favicon/* ./docs --exclude='originals' --exclude="Thumbs.db"
	echo $(CNAME) >> ./docs/CNAME
	hexo generate $(CMD)
generate_watch:
	make generate CMD="--watch"
serve_docs:
	hexo server -s

deploy:
	make generate
	hexo deploy
