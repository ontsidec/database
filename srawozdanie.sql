INSERT INTO kompania(nr_kompanii, kto_dowodzi) VALUES ('1','1');
INSERT INTO pluton(nr_plutonu, nr_kompanii, kto_dowodzi) VALUES ('1','1','2');
INSERT INTO druzyna(nr_druzyny, nr_plutonu, kto_dowodzi) VALUES ('1','1','3');
INSERT INTO nalezy(kto_nalezy, gdzie_nalezy) VALUES ('1','1');
INSERT INTO zolnierz(imie, nazwisko, nr_ksiazeczki, stopien) VALUES ('Franciszek','Pawlowski','AC142913','Kapitan');
INSERT INTO stopnie(nazwa_stopnia) VALUES ('Kapitan');

UPDATE kompania SET nr_kompanii = '2' WHERE id_kompanii = '1';
UPDATE pluton SET nr_plutonu = '2', nr_kompanii = '2' WHERE id_plutonu = '1';
UPDATE druzyna SET nr_druzyny = '2', nr_plutonu = '2' WHERE id_druzyny = '1';
UPDATE nalezy SET kto_nalezy = '1', gdzie_nalezy = '2' WHERE kto_nalezy = '1';
UPDATE zolnierz SET nazwisko = 'Jan', imie = 'Kowalski', stopien = 'Szeregowy' WHERE id_zolnierza = '1';
UPDATE stopnie SET nazwa_stopnia = 'Szeregowy' WHERE id_stopnia = '1';

SELECT z.id_zolnierza, z.imie, z.nazwisko, z.nr_ksiazeczki, s.nazwa_stopnia 
FROM zolnierz z JOIN stopnie s ON  z.stopien = s.id_stopnia 
ORDER BY z.id_zolnierza;