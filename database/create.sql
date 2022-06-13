CREATE TABLE IF NOT EXISTS links(
    id TEXT PRIMARY KEY,
    url TEXT NOT NULL
);

CREATE TABLE views(
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    link_id TEXT,
    ip TEXT NOT NULL,
    browser TEXT NOT NULL,
    os TEXT NOT NULL,
    countryISO TEXT NOT NULL,
    countrySubdivisionISO TEXT NOT NULL,
    access_at TEXT NOT NULL
);