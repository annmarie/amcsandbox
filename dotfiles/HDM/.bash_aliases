# List only directories
alias lsd='ls -l | grep "^d"'

# Enable aliases to be sudo’ed
alias sudo='sudo '

# use Vim not Vi
alias vi="vim"

# For getting to django src dir
alias djangosrc='cd /usr/local/src/django'
alias dsrc='cd /usr/local/src/django'

# IP addresses
alias ip="dig +short myip.opendns.com @resolver1.opendns.com"
alias localip="ipconfig getifaddr en1"
alias ips="ifconfig -a | perl -nle'/(\d+\.\d+\.\d+\.\d+)/ && print $1'"

# Enhanced WHOIS lookups
alias whois="whois -h whois-servers.net"

# Flush Directory Service cache
alias flush="dscacheutil -flushcache"

# View HTTP traffic
alias sniff="sudo ngrep -d 'en1' -t '^(GET|POST) ' 'tcp and port 80'"
alias httpdump="sudo tcpdump -i en1 -n -s 0 -w - | grep -a -o -E \"Host\: .*|GET \/.*\""

# Canonical hex dump; some systems have this symlinked
type -t hd > /dev/null || alias hd="hexdump -C"

# File size
alias fs="stat -f \"%z bytes\""

# URL-encode strings
alias urlencode='python -c "import sys, urllib as ul; print ul.quote_plus(sys.argv[1]);"'

# One of @janmoesen’s ProTips
for method in GET HEAD POST PUT DELETE TRACE OPTIONS; do
	alias "$method"="lwp-request -m '$method'"
done

# set where virutal environments will live
export WORKON_HOME=$HOME/.virtualenvs
# ensure all new environments are isolated from the site-packages directory
export VIRTUALENVWRAPPER_VIRTUALENV_ARGS='--no-site-packages'
# use the same directory for virtualenvs as virtualenvwrapper
export PIP_VIRTUALENV_BASE=$WORKON_HOME
# makes pip detect an active virtualenv and install to it
export PIP_RESPECT_VIRTUALENV=true
if [[ -r /usr/local/bin/virtualenvwrapper.sh ]]; then
    source /usr/local/bin/virtualenvwrapper.sh
else
    echo "WARNING: Can't find virtualenvwrapper.sh"
fi


# Unit Testing
alias mprununit="../vendor/bin/codecept build && ../vendor/bin/codecept run -v --debug"
alias codecover="../vendor/bin/codecept build && ../vendor/bin/codecept run -vvv --coverage --coverage-html"
alias mprununitone="../vendor/bin/codecept run -vvv"

# Seeding the local rover docker container
alias mpseed='docker exec -it edit ./yii seed'
alias mpseedcreate='docker exec -it edit ./yii seed/create'
alias mpseedhtv='docker exec -it edit ./yii seed —theme=htv'

# load xdebug if you run php directly 
alias php='php -dzend_extension=/usr/local/opt/php70-xdebug/xdebug.so'
alias phpunit='php $(which phpunit)'

# Unit Testing
alias unit="../vendor/bin/codecept build && ../vendor/bin/codecept run -v --debug"
alias cover="../vendor/bin/codecept build && ../vendor/bin/codecept run -vvv --coverage --coverage-html"

##
# dc build -no-cache rover if i ever need to rebuild rover
##
dcnew(){
    ENDDIR="/Users/aconway/workspace/media-platform"
    # make sure we are in the correct directory
    if [ $( pwd ) != ${ENDDIR} ]; then
       patty &&
       clear
    fi

    # start dinghy
    dinghy halt &&
    dinghy up &&
    docker-machine env
}

dcup(){
    ENDDIR="/Users/aconway/workspace/media-platform"
    if [ $( pwd ) != ${ENDDIR} ]; then
       patty &&
       clear
    fi
    docker-compose up -d edit fre-theme-hdm fre-theme-shared rover
}

patty(){
    clear &&
    cd /Users/aconway/workspace/media-platform/"$1"/"$2"/"$3"/"$4" &&
    ls -las
}

alias mpflush='docker exec -it edit-redis redis-cli flushdb'
alias mpflushall='docker exec -it edit-redis redis-cli flushall'
alias rediscli='docker exec -it edit-redis redis-cli'


