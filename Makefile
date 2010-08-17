PEAR=pear
PHPUNIT=phpunit
XSLTPROC=xsltproc
CP=cp
MKDIR=mkdir
RM=rm

all : 
	@echo "try :"
	@echo "make release "
	@echo "make push"


#test :
#	$(PHPUNIT) Text_NormalizeTest Text/NormalizeTest.php

push:
	git push
	git push --tags

release: REST_Server-`./extract-version.sh`.tgz

REST_Server-`./extract-version.sh`.tgz: package.xml
	$(PEAR) package package.xml
	git tag -a -m "Version `./extract-version.sh`"  v`./extract-version.sh`
