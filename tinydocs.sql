CREATE TABLE pages (
  id INTEGER PRIMARY KEY AUTOINCREMENT,
  slug varchar(255),
  sort_order integer,
  title integer,
  chapter_type integer,
  unique(slug)
);
CREATE TABLE revisions
(
	id INTEGER PRIMARY KEY AUTOINCREMENT,
	page_id INTEGER,
	rev INTEGER NOT NULL,
	rev_title TEXT,
	rev_slug TEXT,
	content TEXT,
	user_id INTEGER
);
