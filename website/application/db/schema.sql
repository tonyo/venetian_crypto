
CREATE TABLE exercise(id        INTEGER PRIMARY KEY AUTOINCREMENT,
                      text      TEXT NOT NULL,
                      answer    TEXT NOT NULL,
                      algo      TEXT NOT NULL
                );

-- algo is either 'alberti' or 'palgo'

-- New values: Alberti
INSERT INTO exercise(text, answer, algo) VALUES ('1+1?', '2', 'alberti');
INSERT INTO exercise(text, answer, algo) VALUES ('Is Alberti awesome?', 'Yes', 'alberti');
INSERT INTO exercise(text, answer, algo) VALUES ('Decipher the following ciphertext: <em>AzgthpmamgQlfiyky</em>. Use letter <em>g</em> as an initial index letter.', 'LAGVER2RA_SIFARA', 'alberti');

-- New values: Palgo
INSERT INTO exercise(text, answer, algo) VALUES (
    'Encrypt the following message using Alberti cipher: <em>"Welcome to the fabulous city of Venice"</em>. Use letter "M" as a key (starting) letter.',
    'IQXOAYQ FA GUR SNOHYBHF QWHM CT KTCXRT',
    'palgo');
INSERT INTO exercise(text, answer, algo) VALUES (
    'Encrypt the message using Belaso cipher with the passphrase "TABULA": <em>"Venice is under attack"</em>. ',
    'OEOCNE BS VHOEK AUNLCD',
    'palgo');

INSERT INTO exercise(text, answer, algo) VALUES (
    'Decrypt the following message using Vigenere autokey cipher: <em>"TSW WLMFVKB BHRDY JZZ"</em>. Use letter "B" as a key letter',
    'Saw Alberti today lol',
    'palgo');
    
INSERT INTO exercise(text, answer, algo) VALUES (
    'Decrypt the message using Trithemius cipher:
    <em>"APN WLSVQ LEIVL UMA POTEBOLWM UZARPCMTS"</em>. Use letter "H" as a key letter',
    'The Magic Words are Squeamish Ossifrage',
    'palgo');