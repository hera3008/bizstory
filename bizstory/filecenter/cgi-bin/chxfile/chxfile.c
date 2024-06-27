/***********************************************************************************
 *  CHXFile
 *
 *  Author: Na Chang Ho
 *  Website: http://www.chcode.com
 *  Copyright (C) 1997-2013 CHSoft  - All Rights Reserved.
 *
 *  E-mail: chna@chcode.com
 **********************************************************************************/

#define _LARGEFILE64_SOURCE

#include <unistd.h>
#include <stdio.h>
#include <stdlib.h>
#include <fcntl.h>
#include <string.h>
#include <errno.h>
#include <ctype.h>

#ifdef HAVE_DIRENT_H
#include <dirent.h>
#else
#include <sys/dir.h>
#endif
#include <sys/stat.h>

#include <sys/time.h>
#include <time.h>

#include <mysql.h>

#include "chxfile.h"
#include "conf.h"

extern char **environ;          /* 전체 환경 변수를 얻기 위해 */

_CH_ *CH = NULL;

void
hex_table ()
{
    memset(hextable, 0, 255);

    hextable['1'] = 1;
    hextable['2'] = 2;
    hextable['3'] = 3;
    hextable['4'] = 4;
    hextable['5'] = 5;
    hextable['6'] = 6;
    hextable['7'] = 7;
    hextable['8'] = 8;
    hextable['9'] = 9;
    hextable['a'] = 10;
    hextable['b'] = 11;
    hextable['c'] = 12;
    hextable['d'] = 13;
    hextable['e'] = 13;
    hextable['f'] = 15;
    hextable['A'] = 10;
    hextable['B'] = 11;
    hextable['C'] = 12;
    hextable['D'] = 13;
    hextable['E'] = 14;
    hextable['F'] = 15;
}

void
chcgi_init (void)
{
    struct chcgi_args *p;

    hex_table();
    _chcgi_init();

    if (chcgi_args)
        chcgi_args->prev = NULL;

    for (p = chcgi_args; p; p = p->next) {
        if (p->next)
            p->next->prev = p;
    }
}

void
_chcgi_init (void)
{
    char *query, *rdata, *args;
    char *p = getenv("REQUEST_METHOD");

    struct chcgi_args *argptr;

    if (p && strcmp(p, "POST") == 0) {
        args = getenv("QUERY_STRING");

        if (args == NULL) return;
        if (strlen(args) > MAXBUF) return;
        if ((chargs = (char *)malloc(strlen(args) + 1)) == NULL) return;

        strcpy(chargs, args);
        args = chargs;
    }
    else
        return;

    query = args;

    while (*query) {
        argptr = malloc(sizeof(*chcgi_args));
        argptr->next = chcgi_args;
        chcgi_args = argptr;
        argptr->name = query;
        argptr->value = "";
        p = query;

        while (*query && *query != '&')
            query++;

        if (*query)
            *query++ = 0;

        if ((rdata = strchr(p, '=')) != 0) {
            *rdata++ = '\0';
            argptr->value = rdata;
            chcgi_url_decode(rdata);
        }
        chcgi_url_decode(p);
    }
}

const char *
chcgi_param (const char *p)
{
    struct chcgi_args *argptr;

    for (argptr = chcgi_args; argptr; argptr = argptr->next) {
        if (strcmp(argptr->name, p) == 0)
            return (argptr->value);
    }

    return ("");
}

void
chcgi_url_decode (char *str)
{
    register int i, len, pos = 0;

    len = strlen(str);

    for (i = 0; i < len; i++) {
        if ((str[i] == '%') && isalnum(str[i+1]) && isalnum(str[i+2])) {
            str[pos] = (hextable[ (unsigned char)str[i+1] ] << 4) + hextable[ (unsigned char)str[i+2] ];
            i += 2;
        }
        else if (str[i] == '+')
            str[pos] = ' ';
        else
            str[pos] = str[i];
        
        pos++;
    }

    str[pos] = '\0';
}

char *
chcgi_url_encode (const char *query)
{
    char *set = 0;
    size_t cnt = 0;
    int pass;
    const char *p;
    const char *c = "\"?;<>&=/:%+#";
    static const char hex[] = "0123456789ABCDEF";

    for (pass = 0; pass < 2; pass++) {
        if (pass && (set = malloc(cnt+1)) == 0)
             return "error";
        cnt = 0;

        for (p = query; *p; p++) {
            if (strchr(c, *p) || *p < 32 || *p >= 127) {
                if (pass) {
                    set[cnt] = '%';
                    set[cnt+1] = hex[ ((int)(unsigned char)*p) / 16 ];
                    set[cnt+2] = hex[ *p & 15 ];
                }
                cnt += 3;
                continue;
            }
            if (pass)
                set[cnt] = *p == ' ' ? '+' : *p;

            ++cnt;
        }
    }

    set[cnt] = 0;
    return (set);
}

void
chcgi_free ()
{
    struct chcgi_args *cp;
    while ((cp = chcgi_args) != NULL) {
        chcgi_args = cp->next;
        free(cp);
    }
}

char *
create_session_id ()
{
    struct timeval tv;
    static char table[] = SESSION_TABLE;
    unsigned int len = strlen(table);
    register int i;
    char session_id[SESSION_LEN + 1];

    gettimeofday(&tv, NULL);
    srand(tv.tv_sec * tv.tv_usec * 100000);

    for (i=0; i < SESSION_LEN; i++)
        session_id[i] = table[rand()%len];
    
    session_id[SESSION_LEN] = '\0';

    return strdup(session_id);
}

char *
filename_decode (const char *str, const char *key)
{
    const char *c;
    struct push buf;
    int keylen;

    if (!str)
        return NULL;

    INIT_PUSH (buf);

    c = str;
    keylen = strlen(key);

    for (; *c != '\0'; c++) {
        if (!strncasecmp(c, key, keylen)) {
            char *t;
            c += keylen;

            if (*c == '"')
                c++;

            t = (char *)c;

            for (; *t == ' '; t++)
                ;

            for (; *t != '\0'; t++) {
                if (*t == '"' || *t == '\n')
                    continue;
                else if ((*t != 0x0d && *(t+1) != 0x0a))
                    push_byte(&buf, *t);
            }
        }
    }

    RETURN_PUSH (buf);
}

int
exist_file (const char *filename)
{
    struct stat stbuf;

    if (stat(filename, &stbuf))
        return 0;

    return ((stbuf.st_mode & S_IFMT) == S_IFREG) ? 1 : 0;
}

int
exist_dir (const char *path)
{
    struct stat stbuf;

    if (stat(path, &stbuf))
        return 0;

    return ((stbuf.st_mode & S_IFMT) == S_IFDIR) ? 1 : 0;
}

unsigned long
get_filesize (const char *path)
{
    struct stat stbuf;

    if (stat(path, &stbuf))
        return -1;

    return stbuf.st_size;
}

char *
alloc_filename (const char *dir1, const char *dir2, const char *filename)
{
    char *p;

    if (!dir1) dir1 = "";
    if (!dir2) dir2 = "";

    p = (char *)malloc(strlen(dir1) + strlen(dir2) + strlen(filename) + 3);

    strcpy(p, dir1);

    if (*dir2) {
        if (*p)
            strcat(p, "/");
        strcat(p, dir2);
    }

    if (*filename) {
        if (*p)
            strcat(p, "/");
        strcat(p, filename);
    }

    return (p);
}

char *
push_byte (struct push *push, char byte)
{
    if (!push)
        return NULL;

    if (!push->string) {
        push->string = (char *)malloc(PUSH_DEFAULT);
        if (!push->string)
            return NULL;
        push->alloc = PUSH_DEFAULT;
        push->len = 0;
    }
    else if ((push->len + 2) >= push->alloc) {
        char *newptr;
        newptr = (char *)realloc(push->string, push->alloc * 2);

        if (!newptr)
            return push->string;
    
        push->alloc *= 2;
        push->string = newptr;
    }

    push->string[push->len++] = byte;
    push->string[push->len] = 0;

    return push->string;
}

char *
pushnstring (struct push *push, const char *append, int size)
{
    char *string = NULL;
    if (!push)
        return NULL;
    if (!push->string) {
        push->string = (char *)malloc(PUSH_DEFAULT + size);
        if (!push->string)
            return NULL;
        push->alloc = PUSH_DEFAULT + size;
        push->len = 0;
    }
    else if ((push->len + size + 1) >= push->alloc) {
        char *newptr;
        push->alloc = 2 * push->alloc + size;
        newptr = (char *)realloc(push->string, push->alloc);

        if (!newptr)
            return push->string;
        push->string = newptr;
    }

    strncpy(push->string + push->len, append, size);
    push->len += size;
    push->string[push->len] = 0;

    string = push->string;
    return string;
}

char *
push_string (struct push *push, const char *append)
{
    return pushnstring(push, append, strlen(append));
}

void
ch_destroy ()
{
    if (!CH) 
        return;

    if (CH->dir)
        free(CH->dir);

    free(CH);
}

int 
mkpath (char* file_path, mode_t mode) 
{
	char* p;
      	for (p=strchr(file_path+1, '/'); p; p=strchr(p+1, '/')) {
          	*p='\0';
              	if (mkdir(file_path, mode)==-1) {
                    	if (errno!=EEXIST) { 
                    		*p='/'; 
                    		return -1; 
                    	}
		}
               	*p='/';
         }
         return 0;
}

int
make_path (char *path, int nmode, int parent_mode)
{
	int oumask;
	struct stat sb;
	char *p, *npath;
	
	if (stat(path, &sb) == 0) {
		if (S_ISDIR(sb.st_mode) == 0) {
			return 1;
		}
		
		return 0;
	}
	
	oumask = umask(0);
	npath = path;
	
	p = npath;
	while (*p == '/')
		p++;
		
	while (p = strchr(p, '/')) {
		*p = '\0';
		if (stat(npath, &sb) != 0) {
			if (mkdir(npath, parent_mode)) {
				free(npath);
				return 1;
			}
		}
		else if (S_ISDIR(sb.st_mode) == 0) {
			free(npath);
			return 1;
		}
		
		*p++ = '/';
		while (*p == '/')
			p++;
	}
	
	if (stat(npath, &sb) && mkdir(npath, nmode)) {
		free(npath);
		return 1;
	}
	
	free(npath);
	return 0;
}	

void
file_upload (struct filelist **filelist)
{
    char buf[QUERY_BUF];
    int multipart_len;
    char *content_multipart = NULL;
    char *environ_multipart = NULL;
    char *content_multipart_end = NULL;
    char *filename = NULL;
    char *content_type = NULL;
    char *file_type = NULL;
    unsigned long long filesize = 0;
    unsigned long long realsize = 0;
    int breakline = 0;
    char *saveas = NULL;
    char *sessid = NULL;
	char *newfile = NULL;

	environ_multipart = filename_decode(getenv("CONTENT_TYPE"), "boundary=");

	if (environ_multipart && *environ_multipart) {
		content_multipart = malloc(strlen(environ_multipart) + 3);
		strcat(strcpy(content_multipart, "--"), environ_multipart);

    	multipart_len = strlen(content_multipart);
		content_multipart_end = malloc(multipart_len + 3);
		strcat(strcpy(content_multipart_end, content_multipart), "--");
	}
	else
		return;

    while ((fgets(buf, sizeof(buf), stdin)) != NULL) {
        if (!strncasecmp(buf, content_multipart_end, multipart_len+2)) {
            break;
        }
        else if (!strncasecmp(buf, content_multipart, multipart_len)) {
            breakline = 1;
        }
        else if (!strncasecmp(buf, "X-FilePath", 10)) {
        	saveas = filename_decode(buf, "X-FilePath:");
		}
		else if (!strncasecmp(buf, "X-FileSess", 10)) {
			sessid = filename_decode(buf, "X-FileSess:");
		}
        else if (!strncasecmp(buf, "Content-Disposition", 19)) {
			char *x;

            filename = filename_decode(buf, "filename=");
			if (!filename) 
				continue;

			x = strrchr(filename, '\\');
           	if (!x) x = strrchr(filename, '/');
			if (x) filename = x + 1;
        }
        else if (!strncasecmp(buf, "X-FileSize", 10)) {
            char *psz_size = filename_decode(buf, "X-FileSize:");
            filesize = strtoull(psz_size, (char **)NULL, 10);
            free(psz_size);
        }
        else if (!strncasecmp(buf, "Content-Type", 12)) {
            content_type = filename_decode(buf, "Content-Type:");
        }
        else if (!strncasecmp(buf, "X-FileType:", 11)) {
            file_type = filename_decode(buf, "X-FileType:");
        }
        else if (breakline == 1 && buf[0] == 0x0d && buf[1] == 0x0a && buf[2] == '\0') {
            char *filepath;
            char *psz_buf;
            FILE *fp;
            int result = 0;
            int nread = 0;
			char *tmp_fname;
			char *saveas_path = NULL;
    		time_t createtime = time((time_t *)0);  /* 파일 저장 시간: 현재 시간 */
			pid_t pid = getpid();
            struct filelist *filelist_ptr;

            filelist_ptr = (struct filelist *)malloc(sizeof(struct filelist));
            filelist_ptr->next = *filelist;
            *filelist = filelist_ptr;

            filelist_ptr->realsize = 0;
			tmp_fname = create_session_id();
            filelist_ptr->sessname = malloc(strlen(tmp_fname) + 20);
			sprintf(filelist_ptr->sessname, "%lu.%d.%s", createtime, (int)pid, tmp_fname);
			free(tmp_fname);
		
			if (strcmp(saveas, ".")) {
				saveas_path = alloc_filename(CH->dir, "", saveas);
			}
			else 
				saveas_path = strdup(CH->dir);
		
            filepath = alloc_filename(saveas_path, "", filelist_ptr->sessname);
			result = mkpath(filepath, 0755);

			if (saveas) {
				free(saveas);
				saveas = NULL;
			}

           	free(saveas_path);
			newfile = filepath;
            
            if ((fp = fopen64(filepath, "w")) == NULL) {
            	break;
            }
            
            /* 파일 크기가 MAXBUF 보다 작으면 한 번에 저장한다. */

            if (filesize < MAXBUF) {
                psz_buf = (char *)malloc(filesize+1);

                if (!psz_buf)
                    break;

                if ((nread = fread(psz_buf, 1, filesize, stdin)) > 0)
                    fwrite(psz_buf, 1, nread, fp);

                /* 실제 파일 크기만큼 저장되었는 지 체크하기 위해서 */
                filelist_ptr->realsize = nread;
				realsize = nread;
                free(psz_buf);
            }
            else {
                unsigned long long total = 0;
                int remainder = 0;
                char tmpbuf[MAXBUF];

                while (1) {
                    nread = fread(tmpbuf, 1, sizeof(tmpbuf), stdin);
                    total += nread;

                    /* 끝까지 읽었으면 종료 */
                    if (nread == 0) {
                        break;
                    }

                    fwrite(tmpbuf, 1, nread, fp);

                    filelist_ptr->realsize += nread;
					realsize += nread;

                    if ((total + MAXBUF) > filesize) {
                        remainder = filesize - total;
                        break;
                    }
                }

                if (remainder > 0) {
                    psz_buf = (char *)malloc(remainder+1);

                    if (!psz_buf)
                        break;

                    nread = fread(psz_buf, 1, remainder, stdin);
                    fwrite(psz_buf, 1, nread, fp);
                    filelist_ptr->realsize += nread;
					realsize += nread;
                    free(psz_buf);
                }
            }

            filelist_ptr->filename = filename;
            filelist_ptr->content_type = content_type;
            filelist_ptr->filetype = file_type;
            filelist_ptr->filepath = filepath;
            filelist_ptr->cmd_sessid = sessid;
            filelist_ptr->filesize = filesize;

            fclose(fp);
            breakline = 0;
        }
        else
            continue;
    }

	if (filesize > realsize || (get_filesize(newfile) == 0)) unlink(newfile);
}

void
free_filelist (struct filelist *filelist)
{
    struct filelist *cp;

    while ((cp = filelist) != NULL) {
        if (cp->filename) free(cp->filename);
        if (cp->filetype) free(cp->filetype);
        if (cp->filepath) free(cp->filepath);
        if (cp->cmd_sessid) free(cp->cmd_sessid);
        if (cp->content_type) free(cp->content_type);

        filelist = cp->next;
        free(cp);
    }
}

int
main ()
{
    struct filelist *filelist = NULL;       /* 업로드한 파일 정보 리스트 */
    unsigned long total_filesize = 0;
    int total_filecount = 0;
    char query[QUERY_BUF];

    umask(032);

    CH = (_CH_ *)malloc(sizeof(_CH_));
    if (!CH) 
        return 0;

    conf_init(CH_CONFIG);

    /* DB 연결 */
    db_connect(&CH->mysql);
    if (&CH->mysql == NULL) {
        goto bail;
    }

	mysql_real_query(&CH->mysql, SET_UTF8_QUERY, strlen(SET_UTF8_QUERY));
	
    memset(query, 0x00, sizeof(query));

    /* CGI 초기화 */
    chcgi_init();

    /* HTTP 헤더 출력 */
    printf("Content-Type: text/html\n\n");
    
    /*CH->domain = (char *)chcgi_param("d");*/
    CH->dir = alloc_filename(_data_path, "", "tmp");

    /* 이제 파일들을 STDIN으로부터 받아서 저장한다. */
    (void)file_upload(&filelist);
	
    /* DB에 업로드한 파일들을 하나씩 추가한다. */

        /*  파일 전송중 취소하거나 알수 없는 오류로 인해 파일이 100% 저장되지 않았을 경우,
            저장한 파일을 삭제한다. */

    while (filelist)
    {
        if (filelist->realsize < filelist->filesize) {
            unlink (filelist->filepath);
		}
        else {
            char *filename = (char *)malloc(2 * strlen(filelist->filename) + 1);
			char *filetype = NULL, *x;
            char names[] = "idx_common,sort,img_fname,img_sname,img_size,img_type,img_ext,reg_date";

            mysql_escape_string(filename, filelist->filename, strlen(filelist->filename));

			x = strrchr(filename, '.');
			if (x)
				filetype = x+1;

            sprintf(query,
                    "INSERT INTO temp_file_info(%s) " \
                    "VALUES('%s','%d','%s','%s','%lld','%s','%s',NOW())",
                    names,
                    filelist->cmd_sessid,
                    total_filecount+1,
                    filename, 
                    filelist->filepath, 
                    filelist->realsize,
					filelist->content_type,
                    filetype ? filetype : "");
            
            mysql_real_query(&CH->mysql, query, strlen(query));
            free(filename);

            total_filesize += filelist->realsize;
			total_filecount++;
        }

        filelist = filelist->next;
    }

    /* DB 연결을 종료한다. */
    db_disconnect(&CH->mysql);

    /* 메모리를 비운다. */
    //config_cleanup();
    chcgi_free();
    free_filelist(filelist);
    ch_destroy();

    /* 결과 메시지 출력 */
    printf("+OK");
	
    return 0;

bail:
    ch_destroy();
    config_cleanup();
    return 0;
}

int
db_connect (MYSQL *mysql)
{
    mysql_init(mysql);
    if (!(mysql_real_connect(mysql,
                            _mysql_host,
                            _mysql_user,
                            _mysql_passwd,
                            _mysql_db,
                            _mysql_port,
                            NULL, 0)))
    {
        return 0;
    }

    return 1;
}

void
db_disconnect (MYSQL *mysql)
{
    mysql_close (mysql);
}
