PATH=/Users/arrow/bin:/Users/arrow/pybin:$PATH
MANPATH=/opt/local/man:$MANPATH

#PS1='[\t] $S_H \u:[\w]$(__git_ps1 " (%s)")\n'
PS1='\u '

# correct minor misspellings of pathnames
#shopt -s cdspell
#horizontal-scroll-mode on

# check window size and adjust $LINES
shopt -s checkwinsize

export HISTSIZE=10000
export HISTFILESIZE=10000
export HISTCONTROL=ignoredups
export HISTTIMEFORMAT="[%Y-%m-%d - %H:%M:%S] "

function rename_screen_tab () { 
echo -ne "\x1bk$@\x1b\\"; 
echo -ne "\033]0;${USER}@${HOSTNAME%%.*}:${PWD/$HOME/~} \007";
return 0; 
}

case $TERM in
        xterm*|rxvt*|Eterm|aterm)
        PROMPT_COMMAND='echo -ne "\033]0;${USER}\007"'
                ;;
        screen)
	    PROMPT_COMMAND='rename_screen_tab ${USER}@${HOSTNAME%%.*}'
                ;;
esac

 case `id -u` in
       0) PS1="${PS1}# ";;
       *) PS1="${PS1}$ ";;
 esac

export PATH MANPATH PS1

#source ~/.git-completion.sh
