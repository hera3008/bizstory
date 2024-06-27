/***********************************************************************************
 *  CHXFile
 *
 *  Author: Na Chang Ho
 *  Website: http://www.chcode.com
 *  Copyright (C) 1997-2013 CHSoft  - All Rights Reserved.
 *
 *  E-mail: chna@chcode.com
 **********************************************************************************/

#include <stdio.h>
#include <stdlib.h>
#include <string.h>
#include <ctype.h>
#include "conf.h"

char *_data_path;
char *_data_url;
char *_mysql_host;
char *_mysql_user;
char *_mysql_passwd;
char *_mysql_db;
int _mysql_port;

struct config cfg[] = {
    {"data_path",		&_data_path, 		NULL, DEF_STRING },
    {"data_url",		&_data_url, 		NULL, DEF_STRING },
    {"mysql_port", 		&_mysql_port, 		NULL, DEF_INTEGER},
    {"mysql_host", 		&_mysql_host, 		NULL, DEF_STRING},
    {"mysql_user", 		&_mysql_user, 		NULL, DEF_STRING},
    {"mysql_password", 	&_mysql_passwd,		NULL, DEF_STRING},
    {"mysql_database", 	&_mysql_db, 		NULL, DEF_STRING},
};

char *
strsav(const char *string)
{
    char *p;

    if (!string) 
    	string = "";

    p = malloc(strlen(string) + 1);
    strcpy(p, string);

    return p;
}

int 
conf_init(char *filename)
{
    FILE *f = NULL;
    char line[MAXSTRLENGTH];
    int r = TRUE;

    if (filename && *filename) {
		f = fopen(filename, "r");
		
		if (f) {
	    	while (fgets(line, MAXSTRLENGTH, f)) {
				if ((line[0] == '#') || (line[0] == '\n'))
		    		continue;
				config_add_item(line);
	    	}
	    	fclose(f);
		}
		else
	    	r = FALSE;
    }

    return r;
}

int 
config_add_item(char *cfg_line)
{
    char keyword[256];
    char towhat[501];
    char *keywp;
    int i;
    char *line = cfg_line;

    if (2 <= sscanf(line, " %255[a-zA-Z0-9._] %*[=: ] %500[^\n]", keyword, towhat)) {
		if ('\"' == towhat[0]) {
	    	sscanf(line, " %255[a-zA-Z0-9._] %*[=: ] \"%500[^\"]", keyword, towhat);
		}
		else {
	    	i = strlen(towhat);
	    	if (i--) {
				while ((i >= 0) && isspace(towhat[i]))
					towhat[i--] = 0;
	    	}
		}

		keywp = keyword;

		for (i = 0; i < sizeof(cfg) / sizeof(cfg[0]); i++) {
	    	if (*keywp != *(cfg[i].key))
				continue;

	    	if (!strcasecmp(keywp, cfg[i].key)) {
				switch (cfg[i].flags) {
				case DEF_STRING:
		    		*(char **)cfg[i].value = strsav(towhat);
		    		break;
				case DEF_INTEGER:
		    		if (!strcasecmp("ON", towhat) || !strcasecmp("YES", towhat))
						*(int *)cfg[i].value = 1;
		    		else
						*(int *)cfg[i].value = strtol(towhat, NULL, 0);
		    		break;
				case DEF_SWITCH:
		    		if (atoi(towhat) || !strcasecmp("ON", towhat) || !strcasecmp("YES", towhat))
			    		*(int *)cfg[i].value = (int)TRUE;
		    		else
						*(int *)cfg[i].value = (int)FALSE;
		    		break;
				default:
		    		break;
				}
				return 0;
	    	}
		}
    }
    return 1;
}

void 
config_cleanup(void)
{
    int i = 0;

    for (; i < sizeof(cfg) / sizeof(cfg[0]); i++) {
		switch (cfg[i].flags) {
			case DEF_STRING:
	    		if (cfg[i].value) {
					if (*(char **)cfg[i].value)
		    			free(*(char **)cfg[i].value);
	    		}
	    		break;
			default:
	    		break;
		}
    }

	free(_data_path);
	free(_data_url);
}

