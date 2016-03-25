CREATE TABLE statuses_doctypes (
    id integer DEFAULT nextval(('public.statuses_doctypes_id_increment'::text)::regclass) NOT NULL,
    name character varying(128),
    symbolic_id character varying(64)
);

ALTER TABLE public.statuses_doctypes OWNER TO k1sliy;

ALTER TABLE ONLY statuses_doctypes
    ADD CONSTRAINT statuses_doctypes_pkey PRIMARY KEY (id);

ALTER TABLE ONLY statuses_doctypes
    ADD CONSTRAINT statuses_doctypes_symbolic_id_key UNIQUE (symbolic_id);



CREATE TABLE statuses (
    id integer DEFAULT nextval(('public.statuses_id_increment'::text)::regclass) NOT NULL,
    doc_type integer NOT NULL,
    name character varying(128) NOT NULL,
    description character varying(256),
    symbolic_id character varying(64) NOT NULL
);

ALTER TABLE ONLY statuses
    ADD CONSTRAINT statuses_id_symbolic_id_key UNIQUE (id, symbolic_id);

ALTER TABLE ONLY statuses
    ADD CONSTRAINT statuses_pkey PRIMARY KEY (id);

ALTER TABLE ONLY statuses
    ADD CONSTRAINT statuses_doc_type_fkey FOREIGN KEY (doc_type) REFERENCES statuses_doctypes(id) ON UPDATE CASCADE ON DELETE CASCADE;





CREATE TABLE statuses_links 
(    id integer NOT NULL,
    status_from integer NOT NULL,
    status_to integer NOT NULL,
    right_tag character varying(64) NOT NULL
);

CREATE SEQUENCE statuses_links_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;

ALTER SEQUENCE statuses_links_id_seq OWNED BY statuses_links.id;

ALTER TABLE ONLY statuses_links ALTER COLUMN id SET DEFAULT nextval('statuses_links_id_seq'::regclass);

ALTER TABLE ONLY statuses_links
    ADD CONSTRAINT statuses_links_pkey PRIMARY KEY (id);

ALTER TABLE ONLY statuses_links
    ADD CONSTRAINT statuses_links_right_tag_key UNIQUE (right_tag);