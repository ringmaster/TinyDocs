CREATE TABLE pages (
  id INTEGER PRIMARY KEY AUTOINCREMENT,
  slug varchar(255),
  sort_order integer,
  title integer,
  chapter_type integer,
  unique(slug)
);
CREATE TABLE revisions (
  id integer primary key autoincrement,
  page_id integer,
  rev integer not null,
  rev_title varchar(255),
  rev_slug varchar(255),
  content text,
  user_id integer
);