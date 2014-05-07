
CREATE TABLE exercise(id        INTEGER PRIMARY KEY AUTOINCREMENT,
                      text      TEXT NOT NULL,
                      answer    TEXT NOT NULL);


-- New values
INSERT INTO exercise(text, answer) VALUES ('1+1?', '2');
INSERT INTO exercise(text, answer) VALUES ('Is Alberti awesome?', 'Yes');

