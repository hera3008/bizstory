/***********************************************************************************
 *  CHXFile
 *
 *  Author: Na Chang Ho
 *  Website: http://www.chcode.com
 *  Copyright (C) 1997-2013 CHSoft  - All Rights Reserved.
 *
 *  E-mail: chna@chcode.com
 **********************************************************************************/

#ifndef CHXFILE_CONF_H
#define CHXFILE_CONF_H

extern char *_data_path;
extern char *_data_url;
extern char *_mysql_host;
extern char *_mysql_user;
extern char *_mysql_passwd;
extern char *_mysql_db;
extern int _mysql_port;

#define MAXSTRLENGTH 1024
#define DEF_INTEGER 0
#define DEF_STRING 1
#define DEF_SWITCH 2
#define INT(x) (void *)x
#define CH_CONFIG "chxfile.conf"

#undef FALSE
#define FALSE 0
#undef TRUE
#define TRUE 1

typedef int bool;

struct config {
    char *key;
    void *value;
    void *default_value;
    char flags;
};

int conf_init (char *);
void config_cleanup (void);
char *strsav (const char *);
int config_add_item(char *);

#endif /* CHXFILE_CONF_H */
