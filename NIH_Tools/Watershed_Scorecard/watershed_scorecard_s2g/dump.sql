--
-- PostgreSQL database dump
--

-- Dumped from database version 10.6 (Ubuntu 10.6-0ubuntu0.18.04.1)
-- Dumped by pg_dump version 10.6 (Ubuntu 10.6-0ubuntu0.18.04.1)

SET statement_timeout = 0;
SET lock_timeout = 0;
SET idle_in_transaction_session_timeout = 0;
SET client_encoding = 'UTF8';
SET standard_conforming_strings = on;
SELECT pg_catalog.set_config('search_path', '', false);
SET check_function_bodies = false;
SET client_min_messages = warning;
SET row_security = off;

--
-- Data for Name: ci; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.ci (state, district, watershed, year, value) FROM stdin;
CG	kanker	IWMP14	2015	114
\.


--
-- Data for Name: groundwater; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.groundwater (years, area, recharge) FROM stdin;
2015	6673.79004	817.51001
\.


--
-- Data for Name: gwqi; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.gwqi (years, wqi) FROM stdin;
2015	115
\.


--
-- Data for Name: landcap; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.landcap (state, district, watershed, year, class, area) FROM stdin;
CG	kanker	IWMP14	2015	2	17.8182
CG	kanker	IWMP14	2015	4	22.7266
CG	kanker	IWMP14	2015	6	2.0673
CG	kanker	IWMP14	2015	7	3.80433
CG	kanker	IWMP14	2015	8	20.2882
\.


--
-- Data for Name: runoff; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.runoff (years, "annual rainfall", "annual runoff") FROM stdin;
1985	1071.09998	77.0599976
1986	1382	233.089996
1987	1273.30005	124.870003
1988	989.599976	60.0099983
1989	952.299988	19.9200001
1990	2019.40002	257.570007
1991	1044	53.5499992
1992	1445.09998	285.040009
1993	1053.19995	40.5
1994	1499.69995	287.910004
1995	1465	219.059998
1996	968.400024	35.4000015
1997	1364.19995	178.75
1998	1154.69995	67.8799973
1999	1366	197.570007
2000	797.299988	38.0499992
2001	2131.69995	597.960022
2002	886.200012	36.1599998
2003	1308.5	128.75
2004	1209.19995	135.029999
2005	1417.90002	183.970001
2006	2130.69995	423.26001
2007	1428.40002	189.889999
2008	903.799988	47.9300003
2009	950.799988	107.190002
2010	1587.80005	203.619995
2011	1267.30005	88.7300034
2012	1526.40002	175.899994
2013	1364.59998	128.910004
2014	1181.40002	245.070007
2015	1145.5	175.669998
\.


--
-- Data for Name: soildepth; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.soildepth (state, district, watershed, depth, area, year) FROM stdin;
CG	kanker	IWMP14	35	24.7900009	2015
CG	kanker	IWMP14	43	20.2900009	2015
CG	kanker	IWMP14	50	2.75999999	2015
CG	kanker	IWMP14	56	5.03000021	2015
CG	kanker	IWMP14	60	2.97000003	2015
CG	kanker	IWMP14	75	10.8699999	2015
\.


--
-- Data for Name: soilirrig; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.soilirrig (state, district, watershed, year, series, area) FROM stdin;
CG	kanker	IWMP14	2015	A	1.91999996
CG	kanker	IWMP14	2015	B	10.8699999
CG	kanker	IWMP14	2015	C	0
CG	kanker	IWMP14	2015	D	30.5100002
CG	kanker	IWMP14	2015	E	23.3999996
\.


--
-- Data for Name: swqi; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.swqi (state, district, watershed, year, wqi) FROM stdin;
CG	kanker	IWMP14	2015	53.9500008
\.


--
-- PostgreSQL database dump complete
--

