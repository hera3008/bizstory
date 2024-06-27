MYSQL_DIR = /usr/syno/mysql/lib
CC = gcc -Wall
BIN = chxfile.cgi
INSTALL_DIR = .

DEFS = -Imysql_inc
LIBS = -L$(MYSQL_DIR)/mysql -lmysqlclient

OBJS = chxfile.o conf.o

all: $(BIN)

$(BIN): $(OBJS)
	$(CC) -o $(BIN) $(OBJS) $(LIBS)

chxfile.o: chxfile.c
	$(CC) $(DEFS) -o chxfile.o -c chxfile.c

conf.o: conf.c
	$(CC) $(DEFS) -o conf.o -c conf.c

install: $(BIN)
	@echo "Installing..."
	cp chxfile $(INSTALL_DIR)/$(BIN)

clean:
	@echo -n "Cleaning up..."
	@rm -rf *.core core $(BIN) *.o
	@echo "done."
