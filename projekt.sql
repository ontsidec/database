CREATE TABLE stopnie (
    id_stopnia serial primary key,
    nazwa_stopnia varchar(30) not null UNIQUE
);

CREATE TABLE zolnierz (
    id_zolnierza serial primary key,
	imie varchar(20) not null,
	nazwisko varchar(30) not null,
	nr_ksiazeczki char(8) not null UNIQUE,
    stopien int not null REFERENCES stopnie ON DELETE RESTRICT ON UPDATE CASCADE
);

CREATE TABLE kompania (
    id_kompanii serial primary key,
	nr_kompanii smallint not null,
    kto_dowodzi int not null REFERENCES zolnierz ON DELETE RESTRICT ON UPDATE CASCADE
);

CREATE TABLE pluton (
    id_pluton serial primary key,
	nr_plutonu smallint not null,
    nr_kompanii int not null REFERENCES kompania ON DELETE RESTRICT ON UPDATE CASCADE,
    kto_dowodzi int not null REFERENCES zolnierz ON DELETE RESTRICT ON UPDATE CASCADE
);

CREATE TABLE druzyna (
    id_druzyny serial primary key,
	nr_druzyny smallint not null,
    nr_plutonu int not null REFERENCES pluton ON DELETE RESTRICT ON UPDATE CASCADE,
    kto_dowodzi int not null REFERENCES zolnierz ON DELETE RESTRICT ON UPDATE CASCADE
);

CREATE TABLE nalezy (
	kto_nalezy int not null REFERENCES zolnierz ON DELETE RESTRICT ON UPDATE CASCADE,
	gdzie_nalezy int not null REFERENCES druzyna ON DELETE RESTRICT ON UPDATE CASCADE,
	od_kiedy date default now(),
	do_kiedy date,
	primary key (kto_nalezy, gdzie_nalezy, od_kiedy)
);