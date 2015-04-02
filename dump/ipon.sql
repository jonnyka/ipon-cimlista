--
-- PostgreSQL database dump
--

SET statement_timeout = 0;
SET client_encoding = 'UTF8';
SET standard_conforming_strings = on;
SET check_function_bodies = false;
SET client_min_messages = warning;

--
-- Name: plpgsql; Type: EXTENSION; Schema: -; Owner: 
--

CREATE EXTENSION IF NOT EXISTS plpgsql WITH SCHEMA pg_catalog;


--
-- Name: EXTENSION plpgsql; Type: COMMENT; Schema: -; Owner: 
--

COMMENT ON EXTENSION plpgsql IS 'PL/pgSQL procedural language';


SET search_path = public, pg_catalog;

SET default_tablespace = '';

SET default_with_oids = false;

--
-- Name: fos_user; Type: TABLE; Schema: public; Owner: jonny; Tablespace: 
--

CREATE TABLE fos_user (
    id integer NOT NULL,
    username character varying(255) NOT NULL,
    username_canonical character varying(255) NOT NULL,
    email character varying(255) NOT NULL,
    email_canonical character varying(255) NOT NULL,
    enabled boolean NOT NULL,
    salt character varying(255) NOT NULL,
    password character varying(255) NOT NULL,
    last_login timestamp(0) without time zone DEFAULT NULL::timestamp without time zone,
    locked boolean NOT NULL,
    expired boolean NOT NULL,
    expires_at timestamp(0) without time zone DEFAULT NULL::timestamp without time zone,
    confirmation_token character varying(255) DEFAULT NULL::character varying,
    password_requested_at timestamp(0) without time zone DEFAULT NULL::timestamp without time zone,
    roles text NOT NULL,
    credentials_expired boolean NOT NULL,
    credentials_expire_at timestamp(0) without time zone DEFAULT NULL::timestamp without time zone
);


ALTER TABLE public.fos_user OWNER TO jonny;

--
-- Name: COLUMN fos_user.roles; Type: COMMENT; Schema: public; Owner: jonny
--

COMMENT ON COLUMN fos_user.roles IS '(DC2Type:array)';


--
-- Name: fos_user_id_seq; Type: SEQUENCE; Schema: public; Owner: jonny
--

CREATE SEQUENCE fos_user_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.fos_user_id_seq OWNER TO jonny;

--
-- Name: person; Type: TABLE; Schema: public; Owner: jonny; Tablespace: 
--

CREATE TABLE person (
    id integer NOT NULL,
    name character varying(255) NOT NULL,
    emails text NOT NULL,
    phones text NOT NULL,
    addresses text NOT NULL,
    createdat timestamp(0) without time zone NOT NULL,
    updatedat timestamp(0) without time zone NOT NULL
);


ALTER TABLE public.person OWNER TO jonny;

--
-- Name: COLUMN person.emails; Type: COMMENT; Schema: public; Owner: jonny
--

COMMENT ON COLUMN person.emails IS '(DC2Type:array)';


--
-- Name: COLUMN person.phones; Type: COMMENT; Schema: public; Owner: jonny
--

COMMENT ON COLUMN person.phones IS '(DC2Type:array)';


--
-- Name: COLUMN person.addresses; Type: COMMENT; Schema: public; Owner: jonny
--

COMMENT ON COLUMN person.addresses IS '(DC2Type:array)';


--
-- Name: person_id_seq; Type: SEQUENCE; Schema: public; Owner: jonny
--

CREATE SEQUENCE person_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.person_id_seq OWNER TO jonny;

--
-- Data for Name: fos_user; Type: TABLE DATA; Schema: public; Owner: jonny
--

COPY fos_user (id, username, username_canonical, email, email_canonical, enabled, salt, password, last_login, locked, expired, expires_at, confirmation_token, password_requested_at, roles, credentials_expired, credentials_expire_at) FROM stdin;
1	jonny	jonny	jonny@frogz.hu	jonny@frogz.hu	t	yqrobzw2laocss8sk44o8w4gco84cg	Z0z4A5Q0GRJKgQ0zj0/k3jpaMCYqtAi+ZomFCFzAPfM3kwpINgu3v3Ix4rUhpK8WKCJc65Zk4hrloHkxx9NJjw==	\N	f	f	\N	\N	\N	a:1:{i:0;s:16:"ROLE_SUPER_ADMIN";}	f	\N
2	admin	admin	admin@admin.com	admin@admin.com	t	n12x6e17jqooks8k4cg804ogskkwso0	OSaaDCxbD0opa8gvJLwtQFmVm/4rYzukwQ6TOQG3OC9rO8IkeGKnNa7ZbZuQHdBGTeZI/1ig7NZ45tvtgq/vhg==	2015-04-02 15:16:07	f	f	\N	\N	\N	a:1:{i:0;s:16:"ROLE_SUPER_ADMIN";}	f	\N
\.


--
-- Name: fos_user_id_seq; Type: SEQUENCE SET; Schema: public; Owner: jonny
--

SELECT pg_catalog.setval('fos_user_id_seq', 2, true);


--
-- Data for Name: person; Type: TABLE DATA; Schema: public; Owner: jonny
--

COPY person (id, name, emails, phones, addresses, createdat, updatedat) FROM stdin;
1	James Butt	a:1:{i:0;s:15:"jbutt@gmail.com";}	a:2:{i:0;s:12:"504-621-8927";i:1;s:12:"504-845-1427";}	a:3:{i:0;s:18:"6649 N Blue Gum St";i:1;s:19:"4 B Blue Ridge Blvd";i:2;s:20:"8 W Cerritos Ave #54";}	2015-04-02 15:22:23	2015-04-02 15:22:23
2	Art Venere	a:1:{i:0;s:14:"art@venere.org";}	a:1:{i:0;s:12:"856-264-4130";}	a:1:{i:0;s:20:"8 W Cerritos Ave #54";}	2015-04-02 15:22:52	2015-04-02 15:22:52
3	Simona\tMorasca	a:2:{i:0;s:18:"simona@morasca.com";i:1;s:19:"sage_wieser@cox.net";}	N;	N;	2015-04-02 15:23:11	2015-04-02 15:23:11
4	Kiley Caldarera	N;	a:1:{i:0;s:12:"310-254-3084";}	a:1:{i:0;s:16:"25 E 75th St #69";}	2015-04-02 15:23:35	2015-04-02 15:23:35
5	Chanel Caudy	a:2:{i:0;s:22:"chanel.caudy@caudy.org";i:1;s:16:"ezekiel@chui.com";}	a:2:{i:0;s:12:"732-658-3154";i:1;s:12:"732-635-3453";}	a:1:{i:0;s:12:"1048 Main St";}	2015-04-02 15:24:13	2015-04-02 15:24:13
6	Blair\tMalet	a:1:{i:0;s:29:"rozella.ostrosky@ostrosky.com";}	a:1:{i:0;s:12:"323-453-2780";}	a:1:{i:0;s:13:"209 Decker Dr";}	2015-04-02 15:24:42	2015-04-02 15:24:42
7	Willow Kusko	a:1:{i:0;s:16:"wkusko@yahoo.com";}	a:2:{i:0;s:12:"212-582-4976";i:1;s:12:"212-934-5167";}	a:1:{i:0;s:10:"4 Ralph Ct";}	2015-04-02 15:25:20	2015-04-02 15:25:20
8	Ammie Corrio	N;	N;	a:1:{i:0;s:20:"8 W Cerritos Ave #54";}	2015-04-02 15:25:46	2015-04-02 15:25:46
9	Kiley Caldarera	a:2:{i:0;s:16:"asergi@gmail.com";i:1;s:26:"jina_briddick@briddick.com";}	N;	a:1:{i:0;s:13:"209 Decker Dr";}	2015-04-02 15:26:09	2015-04-02 15:26:09
10	Veronika Inouye	N;	a:2:{i:0;s:12:"518-966-7987";i:1;s:12:"913-899-1103";}	a:1:{i:0;s:21:"2742 Distribution Way";}	2015-04-02 15:26:34	2015-04-02 15:26:34
11	Albina Glick	N;	N;	N;	2015-04-02 15:26:45	2015-04-02 15:26:45
12	Bette Nicka	a:1:{i:0;s:19:"bette_nicka@cox.net";}	a:1:{i:0;s:12:"610-492-4643";}	a:1:{i:0;s:11:"6 S 33rd St";}	2015-04-02 15:27:12	2015-04-02 15:27:12
\.


--
-- Name: person_id_seq; Type: SEQUENCE SET; Schema: public; Owner: jonny
--

SELECT pg_catalog.setval('person_id_seq', 12, true);


--
-- Name: fos_user_pkey; Type: CONSTRAINT; Schema: public; Owner: jonny; Tablespace: 
--

ALTER TABLE ONLY fos_user
    ADD CONSTRAINT fos_user_pkey PRIMARY KEY (id);


--
-- Name: person_pkey; Type: CONSTRAINT; Schema: public; Owner: jonny; Tablespace: 
--

ALTER TABLE ONLY person
    ADD CONSTRAINT person_pkey PRIMARY KEY (id);


--
-- Name: uniq_957a647992fc23a8; Type: INDEX; Schema: public; Owner: jonny; Tablespace: 
--

CREATE UNIQUE INDEX uniq_957a647992fc23a8 ON fos_user USING btree (username_canonical);


--
-- Name: uniq_957a6479a0d96fbf; Type: INDEX; Schema: public; Owner: jonny; Tablespace: 
--

CREATE UNIQUE INDEX uniq_957a6479a0d96fbf ON fos_user USING btree (email_canonical);


--
-- Name: public; Type: ACL; Schema: -; Owner: postgres
--

REVOKE ALL ON SCHEMA public FROM PUBLIC;
REVOKE ALL ON SCHEMA public FROM postgres;
GRANT ALL ON SCHEMA public TO postgres;
GRANT ALL ON SCHEMA public TO PUBLIC;


--
-- PostgreSQL database dump complete
--

