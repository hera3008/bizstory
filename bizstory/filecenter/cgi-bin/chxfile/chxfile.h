/***********************************************************************************
 *  CHXFile
 *
 *  Author: Na Chang Ho
 *  Website: http://www.chcode.com
 *  Copyright (C) 1997-2013 CHSoft  - All Rights Reserved.
 *
 *  E-mail: chna@chcode.com
 **********************************************************************************/

#ifndef CHXFILE_H
#define CHXFILE_H

#define SET_UTF8_QUERY "SET NAMES 'utf8'"

typedef struct {
	MYSQL 	 mysql;
	char	*uid;
	char	*cid;
	char	*domain;
	char	*dir;
} _CH_;

struct chcgi_args {
     struct chcgi_args *next;
     struct chcgi_args *prev;
     const char *name;
     const char *value;
};

struct push {
    char *string;
    size_t len;
    size_t alloc;
};

struct filelist {
    char *filename;
    char *filetype;
    char *cmd_sessid;
    char *content_type;
	char *sessname;
	char *filepath;
	unsigned long long realsize;
    unsigned long long filesize;
    struct filelist *next;
};

struct chcgi_args *chcgi_args = NULL;

void  ch_destroy		(void);
void  chcgi_init        (void);
void  _chcgi_init       (void);
void  chcgi_free        (void);
void  chcgi_url_decode  (char *);
char *chcgi_url_encode  (const char *);
char *push_string       (struct push *, const char *);
char *push_byte         (struct push *, char);
char *pushnstring       (struct push *, const char *, int);
const char *chcgi_param (const char *);
int db_connect			(MYSQL *);
void db_disconnect		(MYSQL *);

char *chargs = 0;
static int hextable[256];

#define INIT_PUSH(x) memset(&(x), 0, sizeof(struct push))
#define RETURN_PUSH(x) return (x).string
#define PUSH_DEFAULT 		32

#define SESSION_LEN			45
#define SESSION_TABLE		"123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"

#define MAXBUF 				8196
#define QUERY_BUF 			1024

#endif /* CHXFILE_H */
