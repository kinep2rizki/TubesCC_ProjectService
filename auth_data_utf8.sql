--
-- PostgreSQL database dump
--

\restrict lQfC1r4Ah3WDHZ3c1nU5Vf9Cv1RXjCEwqHafCTVvwJVY908FswqaYoV651CYQjT

-- Dumped from database version 15.17
-- Dumped by pg_dump version 15.17

SET statement_timeout = 0;
SET lock_timeout = 0;
SET idle_in_transaction_session_timeout = 0;
SET client_encoding = 'UTF8';
SET standard_conforming_strings = on;
SELECT pg_catalog.set_config('search_path', '', false);
SET check_function_bodies = false;
SET xmloption = content;
SET client_min_messages = warning;
SET row_security = off;

--
-- Data for Name: permissions; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.permissions (id, name, guard_name, created_at, updated_at) FROM stdin;
1	manage everything	web	2026-06-15 12:11:16	2026-06-15 12:11:16
2	manage events	web	2026-06-15 12:11:16	2026-06-15 12:11:16
3	manage participants	web	2026-06-15 12:11:16	2026-06-15 12:11:16
4	manage attendance	web	2026-06-15 12:11:16	2026-06-15 12:11:16
5	manage certificates	web	2026-06-15 12:11:16	2026-06-15 12:11:16
\.


--
-- Data for Name: model_has_permissions; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.model_has_permissions (permission_id, model_type, model_id) FROM stdin;
\.


--
-- Data for Name: roles; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.roles (id, name, guard_name, created_at, updated_at) FROM stdin;
1	Super Admin	web	2026-06-15 12:11:16	2026-06-15 12:11:16
\.


--
-- Data for Name: model_has_roles; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.model_has_roles (role_id, model_type, model_id) FROM stdin;
1	App\\Models\\User	128
\.


--
-- Data for Name: personal_access_tokens; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.personal_access_tokens (id, tokenable_type, tokenable_id, name, token, abilities, last_used_at, expires_at, created_at, updated_at) FROM stdin;
\.


--
-- Data for Name: role_has_permissions; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.role_has_permissions (permission_id, role_id) FROM stdin;
1	1
2	1
3	1
4	1
5	1
\.


--
-- Data for Name: users; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.users (id, name, email, email_verified_at, password, avatar_url, remember_token, created_at, updated_at) FROM stdin;
14	Rizki Saputra	rizkisaputra14@peta.id	2026-06-15 12:11:17	$2y$12$ERaOddOIBBsGccgUZF/7o.bCkTTbzRJX4Dw9c71VEnyjD7SNwnfXq	\N	vmgLFud5N1	2026-06-15 12:11:17	2026-06-15 16:46:10
15	Farhan Firmansyah	farhanfirmansyah15@peta.id	\N	$2y$12$WLqlXyz4nl3oWkeW1tOq.uvIrq2edD.6BHY6wBgdD.qaFYzAh15/K	\N	\N	2026-06-15 12:52:10	2026-06-15 16:46:10
16	Raka Ramadhan	rakaramadhan16@peta.id	\N	$2y$12$Bq1jGwy0m4GdabbA0v/30u9z0EBshlOJ0N..djFqOaaJVHNVH/K.u	\N	\N	2026-06-15 13:26:11	2026-06-15 16:46:11
17	Bima Hidayat	bimahidayat17@peta.id	\N	$2y$12$34FrJG/ivuxJufO48suD7uQqUHHRyFHtZdep1bOj9ZcNXWnEabd22	\N	\N	2026-06-15 16:46:11	2026-06-15 16:46:11
18	Fajar Pratama	fajarpratama18@peta.id	\N	$2y$12$p/hgfhTFTDgRXxz3tmTgJebq5/9TfmUC2vP73.79c/OvwxIv1GOlW	\N	\N	2026-06-15 16:46:11	2026-06-15 16:46:11
19	Aulia Pratama	auliapratama19@peta.id	\N	$2y$12$nY5G59sZ5XwxgioMdHeNvuC9mH4eEb8oYWkSAvCcrXdaEaMTz/ZsG	\N	\N	2026-06-15 16:46:11	2026-06-15 16:46:11
20	Bima Kurniawan	bimakurniawan20@peta.id	\N	$2y$12$JMkR5qJ5/uAVIvK8wnyTyeQ20FpK/snpyQB6aPIellZ1C1blyNx.e	\N	\N	2026-06-15 16:46:11	2026-06-15 16:46:11
2	Rizki Maulana	rizkimaulana2@peta.id	2026-06-15 12:11:17	$2y$12$hitm8lMrYBxuTwuSKhuU6ewrDREQz7MLSoSL5bBh.H.boqVzEayY2	\N	XdhMA5EzBs	2026-06-15 12:11:17	2026-06-15 16:46:08
3	Nabila Firmansyah	nabilafirmansyah3@peta.id	2026-06-15 12:11:17	$2y$12$O8FWRRBWs5Ai.SuSWV4CfeFrNzs75zlzIORIl10eBBk37E7ZdSFbm	\N	6Yhdp92MLr	2026-06-15 12:11:17	2026-06-15 16:46:08
4	Bima Ramadhan	bimaramadhan4@peta.id	2026-06-15 12:11:17	$2y$12$6k6wO7iCDV9xDGtYUic.c./M109NqqLSQ6BK89LaDsIMbPGaDp7si	\N	IwvrU3yLvq	2026-06-15 12:11:17	2026-06-15 16:46:08
5	Fikri Wijaya	fikriwijaya5@peta.id	2026-06-15 12:11:17	$2y$12$EdMFPP23jGXSPF6MDFAyY.u3DveW9P2tT3wkwyEdn3luANgdmR5kK	\N	GLvMZN0VNI	2026-06-15 12:11:17	2026-06-15 16:46:08
6	Nabila Maulana	nabilamaulana6@peta.id	2026-06-15 12:11:17	$2y$12$XqVCbhtGSIfD622tXCjAGuzdl15TUYZbCaoyJEgUWNcPgsdiWdhxO	\N	xKGNHLnBJb	2026-06-15 12:11:17	2026-06-15 16:46:08
7	Farhan Firmansyah	farhanfirmansyah7@peta.id	2026-06-15 12:11:17	$2y$12$.P5jLv7rDcX.yzDZoIQ62eh7yXJbOJ8TpUO3ZWYjpbU1rHonKh1um	\N	vRCSwM5bdJ	2026-06-15 12:11:17	2026-06-15 16:46:09
8	Aulia Wijaya	auliawijaya8@peta.id	2026-06-15 12:11:17	$2y$12$hldwO441WLmSCC3HgODHy.Xo8n1TdwfPHscGsSeI2tc8vrYYndfFS	\N	zCNiNXc9xi	2026-06-15 12:11:17	2026-06-15 16:46:09
9	Yoga Ramadhan	yogaramadhan9@peta.id	2026-06-15 12:11:17	$2y$12$H2QGyVe9EX8R7fu56IIm1eqW4MpkxtXgkVAlW4Em274G78tAxaHoS	\N	Oo1ENG9xrH	2026-06-15 12:11:17	2026-06-15 16:46:09
10	Farhan Nugroho	farhannugroho10@peta.id	2026-06-15 12:11:17	$2y$12$.E5omkSWJcgCv4duA6G7ZOQU6CyGNVfVGwp44gGcfem.NxtPbWQYu	\N	hwqpYs85Ff	2026-06-15 12:11:17	2026-06-15 16:46:09
11	Rizki Firmansyah	rizkifirmansyah11@peta.id	2026-06-15 12:11:17	$2y$12$TQVWyUOBUGYaYJKueZG.Fupp4Vxdyj0jz8SXN8IqByIPfC/IHx5ge	\N	iGKdTtsmJ5	2026-06-15 12:11:17	2026-06-15 16:46:10
12	Tiara Firmansyah	tiarafirmansyah12@peta.id	2026-06-15 12:11:17	$2y$12$x.RXqAhTyQAYkLZlsCSjT.nYszzgp.qgyH/fGIqLR5EsCLCRJIbCu	\N	g0hksqM4kt	2026-06-15 12:11:17	2026-06-15 16:46:10
13	Putri Firmansyah	putrifirmansyah13@peta.id	2026-06-15 12:11:17	$2y$12$Uw4h8f284h9yotB7yU7iGuFK95A3f0z2Y4h.6X/B1ENjCFb8jffGK	\N	aZuVRGVY2s	2026-06-15 12:11:17	2026-06-15 16:46:10
21	Nabila Wijaya	nabilawijaya21@peta.id	\N	$2y$12$70QPYPv7iAlFujWMvirvg.BxPbi4Unr6PSwy9T6FKFeoHCStGrhmm	\N	\N	2026-06-15 16:46:12	2026-06-15 16:46:12
22	Aulia Nugroho	aulianugroho22@peta.id	\N	$2y$12$gWuvegvniRq9zdtlEzfKk.IEA61oNhaI04SAAzQ0UkJwIc6VfWUNO	\N	\N	2026-06-15 16:46:12	2026-06-15 16:46:12
23	Yoga Wijaya	yogawijaya23@peta.id	\N	$2y$12$OjnQoGdXq4FuqORk0SJnCubuDwjGMGJEzBA1g6niLstBVfvq29Ecy	\N	\N	2026-06-15 16:46:12	2026-06-15 16:46:12
24	Bima Permana	bimapermana24@peta.id	\N	$2y$12$F00SUt9MaGOvF3B4.BKZk.GYNq8jaGow/d4Jhfj56hEjC8PTntebO	\N	\N	2026-06-15 16:46:12	2026-06-15 16:46:12
25	Farhan Kurniawan	farhankurniawan25@peta.id	\N	$2y$12$CYPLgDEmKU7.UhGwp4RmxukB2din7jfF9ZlMyZUiePfgkQ/S18W52	\N	\N	2026-06-15 16:46:13	2026-06-15 16:46:13
26	Raka Pratama	rakapratama26@peta.id	\N	$2y$12$CgYb5E9aHkz0IewBZShVR.GmETz37RFfQCJG4V6gcPBBkqPdoOj9C	\N	\N	2026-06-15 16:46:13	2026-06-15 16:46:13
27	Aulia Saputra	auliasaputra27@peta.id	\N	$2y$12$/JwBT/QAi1TSzVb5Ji98uuWSe6RBdRb4DOqW.uCzHGaQp4r66Qk/2	\N	\N	2026-06-15 16:46:13	2026-06-15 16:46:13
28	Fajar Saputra	fajarsaputra28@peta.id	\N	$2y$12$m2LpGq7hsH.zQUweIMGOYuCS.7MzBofsRU1/LLHdqLPteffFI8vFG	\N	\N	2026-06-15 16:46:13	2026-06-15 16:46:13
29	Fikri Wijaya	fikriwijaya29@peta.id	\N	$2y$12$1.MRYabFhB5JZPsPcOZ.cu2m2tVWfz0i4nRTn.YFJjDdCnKs6trFO	\N	\N	2026-06-15 16:46:13	2026-06-15 16:46:13
30	Raka Wijaya	rakawijaya30@peta.id	\N	$2y$12$XlAAd3MbIal4CiQt5x9Os.XWC5WZNRxmd1dkrFSgGS6yQl8DWXxRm	\N	\N	2026-06-15 16:46:14	2026-06-15 16:46:14
31	Andi Kurniawan	andikurniawan31@peta.id	\N	$2y$12$UnjfNLjaUA9W0fXF6X.HNelL.hImRZuuG8Dg4In6TODM263AOp64i	\N	\N	2026-06-15 16:46:14	2026-06-15 16:46:14
32	Yoga Ramadhan	yogaramadhan32@peta.id	\N	$2y$12$7fDrkcmQtxLB4dr6bTIkcuxsDhfP8Zk1oclrnRgsI8rA8GKWSp3Fq	\N	\N	2026-06-15 16:46:14	2026-06-15 16:46:14
33	Bima Permana	bimapermana33@peta.id	\N	$2y$12$OmAMZLned40OPXKblot/9uqCU8NKNsbb248m7wDfpRU.GzOTMeyyi	\N	\N	2026-06-15 16:46:14	2026-06-15 16:46:14
34	Aulia Firmansyah	auliafirmansyah34@peta.id	\N	$2y$12$wFKnTZJvgpHISddqH.R56.3.zqcePYk4s9o4ODkHH9DFF3THXlr26	\N	\N	2026-06-15 16:46:15	2026-06-15 16:46:15
35	Farhan Permana	farhanpermana35@peta.id	\N	$2y$12$t4Pn4dVDRNuQZulmMMnf0.AYZn5elF800ctAtpKV.7xJxmroVkYiS	\N	\N	2026-06-15 16:46:15	2026-06-15 16:46:15
36	Nabila Permana	nabilapermana36@peta.id	\N	$2y$12$bCO1PtVrhMn7ZjY7DG2DTemRcp1TcXmHMVXCl.a84kqPhcx9uJIIy	\N	\N	2026-06-15 16:46:15	2026-06-15 16:46:15
37	Putri Wijaya	putriwijaya37@peta.id	\N	$2y$12$wFy2HEOgRpOwBsTEMyR0J.iUMYviqK3VvRVfR.mYWJkM9g3VCJ2aC	\N	\N	2026-06-15 16:46:15	2026-06-15 16:46:15
38	Dimas Saputra	dimassaputra38@peta.id	\N	$2y$12$ocy.7NxXqQgaAKeHQjgVWuFyqhE90SeVQBIGny0FuQbjsSpD.rTwO	\N	\N	2026-06-15 16:46:15	2026-06-15 16:46:15
39	Fikri Firmansyah	fikrifirmansyah39@peta.id	\N	$2y$12$OOcfiWSQGVZYUJCIY1vjteOYmR4wsaoNeCw2/gFSpyFevqttTLQ9u	\N	\N	2026-06-15 16:46:16	2026-06-15 16:46:16
40	Raka Hidayat	rakahidayat40@peta.id	\N	$2y$12$j.nA6HD7gVhcsCQ13g6A3OL0CziitJeYrOoCs2Z01xzcbPK7m4OcS	\N	\N	2026-06-15 16:46:16	2026-06-15 16:46:16
41	Andi Wijaya	andiwijaya41@peta.id	\N	$2y$12$0VekV17vvBccLjVEQcAj7.0X.ozaEtYAy2vLFw0Q5.dkVZmyKbvNG	\N	\N	2026-06-15 16:46:16	2026-06-15 16:46:16
42	Farhan Hidayat	farhanhidayat42@peta.id	\N	$2y$12$.jAnFnZFtzfkssYlUbgVTO5mZGd1QuTgsJKJaV4gJm62dbwp83fIa	\N	\N	2026-06-15 16:46:16	2026-06-15 16:46:16
43	Tiara Pratama	tiarapratama43@peta.id	\N	$2y$12$R6OCpIeXagLe7uYbcpfzl.eTeL2z1iUzwcAMnly1kE37iFxFDHNBa	\N	\N	2026-06-15 16:46:17	2026-06-15 16:46:17
44	Yoga Maulana	yogamaulana44@peta.id	\N	$2y$12$UrNL9hHG13c/D78lZX0KPuC4n/nMva6ECaXkhL03qUHqszSseJx.m	\N	\N	2026-06-15 16:46:17	2026-06-15 16:46:17
45	Naufal Pratama	naufalpratama45@peta.id	\N	$2y$12$eOtGl6pKLGX4UG7fHI2VQen2vv0x./Vijm37XP7VixTFFAxNMjrjq	\N	\N	2026-06-15 16:46:17	2026-06-15 16:46:17
1	Bima Nugroho	bimanugroho1@peta.id	2026-06-15 12:11:16	$2y$12$0mH0YrP25XFyflyNeut7B.MJCL95PJoCMS.Mj6bueGUr7ScIjJTOq	\N	cHsQ96j20XfaCix3KRNbyA174NTPC2CGCmdIOYkvyMrsqSB96QRf9dkAg5Yx	2026-06-15 12:11:17	2026-06-15 16:46:07
46	Fajar Nugroho	fajarnugroho46@peta.id	\N	$2y$12$IZc.TJAMQmfiSLIa.zN1H.QVMg.PhS/ck3BCW241qAcrVFKHMD.yO	\N	\N	2026-06-15 16:46:17	2026-06-15 16:46:17
47	Fikri Hidayat	fikrihidayat47@peta.id	\N	$2y$12$aSgC19vij1oJvUA9T88.9eQ/nfEa1UtGMhKsg5lWbBZKL2funlAw2	\N	\N	2026-06-15 16:46:17	2026-06-15 16:46:17
48	Raka Maulana	rakamaulana48@peta.id	\N	$2y$12$FlzuaavTdPSo9XL.ASeepe.wg80OMD3I.byTxykOAMH7KwBZWWcAu	\N	\N	2026-06-15 16:46:18	2026-06-15 16:46:18
49	Andi Permana	andipermana49@peta.id	\N	$2y$12$7tkm4twNX.2LmRlN2w9x0eo6njfALey1iiwNlknjQpSCjw04pN9nO	\N	\N	2026-06-15 16:46:18	2026-06-15 16:46:18
50	Raka Firmansyah	rakafirmansyah50@peta.id	\N	$2y$12$/qAaCqh/3fyD8bcJTPTPReL76NoaFir4inPJ5JEJHyCYFKyTlOMQq	\N	\N	2026-06-15 16:46:18	2026-06-15 16:46:18
51	Fikri Kurniawan	fikrikurniawan51@peta.id	\N	$2y$12$sXF6atmAEWD2546DR4yzZeaaSmFa4Hs7KeMCHzSVZ04LKeuKhAPH6	\N	\N	2026-06-15 16:46:18	2026-06-15 16:46:18
52	Tiara Maulana	tiaramaulana52@peta.id	\N	$2y$12$UCd93.bnKTG8IBVNVHdUE.3uubpUNygna7PJ5KHPDyuLDgl5mz/TK	\N	\N	2026-06-15 16:46:19	2026-06-15 16:46:19
53	Raka Ramadhan	rakaramadhan53@peta.id	\N	$2y$12$7nv2oq0fyGnAtHGoaLvjBeYTXXL06EsKSWDaU/1NCMvbqOJNXh.Hi	\N	\N	2026-06-15 16:46:19	2026-06-15 16:46:19
54	Fajar Firmansyah	fajarfirmansyah54@peta.id	\N	$2y$12$RWxy.ZQclbPQly3uzidgK.Xpjyb/6ivpBSlDjYBPZlUUMEtqqEtHa	\N	\N	2026-06-15 16:46:19	2026-06-15 16:46:19
55	Dimas Firmansyah	dimasfirmansyah55@peta.id	\N	$2y$12$xIljpUQsoyXWi0cn/KLuyeAg8eff3PpiiUpS1u/MEnwOy0bHd6gC.	\N	\N	2026-06-15 16:46:19	2026-06-15 16:46:19
56	Naufal Ramadhan	naufalramadhan56@peta.id	\N	$2y$12$f8qKNetet4eL38WQuLERXuF1ymBcXGl4soj5rH4AnWgq8WLgZQ.Zu	\N	\N	2026-06-15 16:46:19	2026-06-15 16:46:19
57	Yoga Wijaya	yogawijaya57@peta.id	\N	$2y$12$nnCAd0VMIfrKa76XfSB.f.nMSbWTp9SNcvkRvoAkTe6g/8df.TuMy	\N	\N	2026-06-15 16:46:20	2026-06-15 16:46:20
58	Fikri Maulana	fikrimaulana58@peta.id	\N	$2y$12$.qSJYmNfg/q6n5LB3H3Xcuyzij6PY4Fz4kNSIPtFjxunZxsPgjmKW	\N	\N	2026-06-15 16:46:20	2026-06-15 16:46:20
59	Dimas Permana	dimaspermana59@peta.id	\N	$2y$12$oC2MKCVd.Vd76pVAS84HKuqiQwpOBZcqT4848NN3H5so2epfhsb8y	\N	\N	2026-06-15 16:46:20	2026-06-15 16:46:20
60	Fajar Saputra	fajarsaputra60@peta.id	\N	$2y$12$lCGFKEHHj9jlpc2tKn7xzOMoPTRLWRpaEegHXGOd6bkAwnStWWTz6	\N	\N	2026-06-15 16:46:20	2026-06-15 16:46:20
61	Bima Pratama	bimapratama61@peta.id	\N	$2y$12$XYv7h25vQh5hhWfIinFhTOq8dGTXYt7cQiucQ6IQEiGFnpvxREjvS	\N	\N	2026-06-15 16:46:21	2026-06-15 16:46:21
62	Tiara Permana	tiarapermana62@peta.id	\N	$2y$12$j8gAyl9tf7d1EqmA2sDPpeP/KpFGepymAPVnl2D.EoaZ9KCDmnxS.	\N	\N	2026-06-15 16:46:21	2026-06-15 16:46:21
63	Naufal Wijaya	naufalwijaya63@peta.id	\N	$2y$12$WW5CKsml6osV3DLyCJ/dFupr0oXp/TICLraMNQinb8gCIgHfUPSXi	\N	\N	2026-06-15 16:46:21	2026-06-15 16:46:21
64	Dimas Hidayat	dimashidayat64@peta.id	\N	$2y$12$j6EymeP12jw6TUXG3PDykOnmu7bQjx5UrhN5iCRFIlxfhkz4T2HEO	\N	\N	2026-06-15 16:46:21	2026-06-15 16:46:21
65	Fikri Ramadhan	fikriramadhan65@peta.id	\N	$2y$12$ARkqlqweQJQlt2GUazj/e.B.epQHwHUXmMTntHKWlfxeGkMYa7LIq	\N	\N	2026-06-15 16:46:21	2026-06-15 16:46:21
66	Dimas Ramadhan	dimasramadhan66@peta.id	\N	$2y$12$z8kvKyJEcG1rKYhQwQguMOVb6AjAeX0YSHtYAOxUynkgBORkNUxQS	\N	\N	2026-06-15 16:46:22	2026-06-15 16:46:22
67	Akbar Wijaya	akbarwijaya67@peta.id	\N	$2y$12$cvTI66faUz7Yror.TKPnOutUdTGFZvj690cyBoJS8QnkaBJ3asr6G	\N	\N	2026-06-15 16:46:22	2026-06-15 16:46:22
68	Farhan Maulana	farhanmaulana68@peta.id	\N	$2y$12$VGh/G3W84T.NWsp41SeESO8bfCmo3qrTrif74/grh9gZb2MqCnJFi	\N	\N	2026-06-15 16:46:22	2026-06-15 16:46:22
69	Dimas Permana	dimaspermana69@peta.id	\N	$2y$12$NPiUYLiHb2Mc/CL8A9BdaOfAC5kv92cNMywvLu35I5trynNGlwONS	\N	\N	2026-06-15 16:46:22	2026-06-15 16:46:22
70	Yoga Kurniawan	yogakurniawan70@peta.id	\N	$2y$12$l3txdKx4eBcKH0bQiCYpJeh313aOLnvcoS4Veaso/d8r6CddSu6Mu	\N	\N	2026-06-15 16:46:22	2026-06-15 16:46:22
71	Andi Firmansyah	andifirmansyah71@peta.id	\N	$2y$12$z.Pk.6KQCcWzqIW4Ng9pUehf.mDF0GNyl8xS.2J0Iv6MdKhX1fbGi	\N	\N	2026-06-15 16:46:23	2026-06-15 16:46:23
72	Putri Firmansyah	putrifirmansyah72@peta.id	\N	$2y$12$PIjQKfNLWV5cbI3O8H1JjOeW0LDEVUtzIEK1qo9WfSFP9VYlL3b6.	\N	\N	2026-06-15 16:46:23	2026-06-15 16:46:23
73	Raka Firmansyah	rakafirmansyah73@peta.id	\N	$2y$12$7Ji/lB.tN5ldIvWpVNjhY.2edSlTrQKK1UWV345I4pPG/dJqhIoCi	\N	\N	2026-06-15 16:46:23	2026-06-15 16:46:23
74	Andi Hidayat	andihidayat74@peta.id	\N	$2y$12$VNnHcJ.q3jLNjUqgNgNOD.swbNhp5Zm9ulXZ5OmawrSo/vLZn0vcm	\N	\N	2026-06-15 16:46:23	2026-06-15 16:46:23
75	Rizki Pratama	rizkipratama75@peta.id	\N	$2y$12$HaGlgVf53j8UlGjH4l76QOFMF0gXEBHCnWFsyk8dyg7Qb3MYDfxa.	\N	\N	2026-06-15 16:46:24	2026-06-15 16:46:24
76	Akbar Pratama	akbarpratama76@peta.id	\N	$2y$12$K8p0u.BaCcj1UMWF1pLcAuqFdpbQ3mmO0jc3D/MJTorPu9FVsUpJa	\N	\N	2026-06-15 16:46:24	2026-06-15 16:46:24
77	Andi Firmansyah	andifirmansyah77@peta.id	\N	$2y$12$gpYuPjgHeAaK1C1MN8fNoOIEz60iQMHGLheEqui2DqCpT2XZBqmgC	\N	\N	2026-06-15 16:46:24	2026-06-15 16:46:24
78	Rizki Maulana	rizkimaulana78@peta.id	\N	$2y$12$6Bhc4WjESwaQdlvqUTlN4e9NCd9TdAGq8yczlqdUlMMUevOc85xg.	\N	\N	2026-06-15 16:46:24	2026-06-15 16:46:24
79	Nabila Ramadhan	nabilaramadhan79@peta.id	\N	$2y$12$rud0llxdaIW3Tj0mV8/gAuMa.w4EUpRsveDDI9J6COrx5QRIWBif.	\N	\N	2026-06-15 16:46:24	2026-06-15 16:46:24
80	Rizki Nugroho	rizkinugroho80@peta.id	\N	$2y$12$QfzmvszgXsskOPGFn7HMdeSYkh/mNRFQq5pvLvH5OTaAsDSg0kl9.	\N	\N	2026-06-15 16:46:25	2026-06-15 16:46:25
81	Raka Maulana	rakamaulana81@peta.id	\N	$2y$12$GEWpcE/kzFhHbv0EDAo0JuRsL4RvuVlWSXNL3YBEVZ9SYqaiwS0Ja	\N	\N	2026-06-15 16:46:25	2026-06-15 16:46:25
82	Dimas Maulana	dimasmaulana82@peta.id	\N	$2y$12$4l7/xgpnwwkGanKxz/k4Ru/ef6i5igv6Tf92uQqD3W5kQN7UlaU8G	\N	\N	2026-06-15 16:46:25	2026-06-15 16:46:25
83	Naufal Firmansyah	naufalfirmansyah83@peta.id	\N	$2y$12$yYivgQx6Rr3rL0s6ZxcE/uIZgAqlsw/k0QWwLU014cJ2gGDNy7IEW	\N	\N	2026-06-15 16:46:25	2026-06-15 16:46:25
84	Fajar Permana	fajarpermana84@peta.id	\N	$2y$12$i7l78qzOV8a.hYFd8y4PWet6/35eMfhR.Hm/lkBIgGPzXFLX0bXgm	\N	\N	2026-06-15 16:46:25	2026-06-15 16:46:25
85	Nabila Pratama	nabilapratama85@peta.id	\N	$2y$12$S0ubIbtQx5YVwJBJ8e/7MOI1aKPmrGqHbSlh94RbOC09I/aZK9f0y	\N	\N	2026-06-15 16:46:26	2026-06-15 16:46:26
86	Putri Nugroho	putrinugroho86@peta.id	\N	$2y$12$/8u9ZGEX7BNAwSOvc58go.kRv8goSccaWEonGTs3eSfpaBk/oNcfm	\N	\N	2026-06-15 16:46:26	2026-06-15 16:46:26
87	Yoga Ramadhan	yogaramadhan87@peta.id	\N	$2y$12$g1Tl8fl6E/VPaKjNL6j9Z.ClBs/R2AxxEN1vQhpZhUAi3nvDWbGBW	\N	\N	2026-06-15 16:46:26	2026-06-15 16:46:26
88	Yoga Saputra	yogasaputra88@peta.id	\N	$2y$12$40Fh7XCIsqQXRkcwLbci7uqd3bQtSEfN2sLQucHpgSkTGfl1/1pi.	\N	\N	2026-06-15 16:46:26	2026-06-15 16:46:26
89	Akbar Kurniawan	akbarkurniawan89@peta.id	\N	$2y$12$STVxdNSSgfHo.dvcQ6.vHOQtwMJuIQg2TnYWfaHDIZ4sRGBZMGML.	\N	\N	2026-06-15 16:46:27	2026-06-15 16:46:27
90	Yoga Permana	yogapermana90@peta.id	\N	$2y$12$Yi9hhniZi0fyxDmxgw38e.OYVNgd9lNW6UVjKU2VcDsr4kUXc3ccy	\N	\N	2026-06-15 16:46:27	2026-06-15 16:46:27
91	Naufal Hidayat	naufalhidayat91@peta.id	\N	$2y$12$z88UM9rRFjgtslGAXQcdT.2glOzrgAjcYo476aFYaj92uWG6YgJvG	\N	\N	2026-06-15 16:46:27	2026-06-15 16:46:27
92	Yoga Hidayat	yogahidayat92@peta.id	\N	$2y$12$.3ihQuPXQdtNvxQp6unBI.PK0l5uodVJnQehYDFDVV/H15xAeXOqe	\N	\N	2026-06-15 16:46:27	2026-06-15 16:46:27
93	Tiara Saputra	tiarasaputra93@peta.id	\N	$2y$12$moU95roKTTS9mMRFcbOBjeV3SqO8Mz6jupEuhTCNfaFd1riCtEPA6	\N	\N	2026-06-15 16:46:28	2026-06-15 16:46:28
94	Fikri Saputra	fikrisaputra94@peta.id	\N	$2y$12$ZDDn34X8EGpLFkHLC4Aom.QvCqa4qjIoRNHsf/SH8Bk0l8lo8sJJS	\N	\N	2026-06-15 16:46:28	2026-06-15 16:46:28
95	Fikri Firmansyah	fikrifirmansyah95@peta.id	\N	$2y$12$YxK9gAM9cbpAnGJP4yNi4OHZJ6sb5kGOkbRw8GYZz0vOnKrWRR9JW	\N	\N	2026-06-15 16:46:28	2026-06-15 16:46:28
96	Nabila Permana	nabilapermana96@peta.id	\N	$2y$12$DjLkis9agkOQQ8KZlbJ4Z.lMCPzYa/HxTXTdVN/C7M0ELuVcdxnv2	\N	\N	2026-06-15 16:46:28	2026-06-15 16:46:28
97	Yoga Pratama	yogapratama97@peta.id	\N	$2y$12$8Nk3F7jp2DfujaxXBrO3MesnSDyfJe5XMQthQMEr/wdL9PLqyCBIy	\N	\N	2026-06-15 16:46:28	2026-06-15 16:46:28
98	Tiara Ramadhan	tiararamadhan98@peta.id	\N	$2y$12$bAWXrOsrGVfG.7b1PhogpevIEiiadt1u/voCPyKfIPjEIv0NIL46G	\N	\N	2026-06-15 16:46:29	2026-06-15 16:46:29
99	Rizki Nugroho	rizkinugroho99@peta.id	\N	$2y$12$dAPBe/pBzaFFr8RZGUEXEeEbRSoj0EmWQAU0IBbD4Yo1r4m5bhZAm	\N	\N	2026-06-15 16:46:29	2026-06-15 16:46:29
100	Akbar Kurniawan	akbarkurniawan100@peta.id	\N	$2y$12$aOJyMnk4OngaR9tom.dHEuzPzwuZ5gWuPPD8lL6PK2oQlxjgW49/G	\N	\N	2026-06-15 16:46:29	2026-06-15 16:46:29
101	Fikri Firmansyah	fikrifirmansyah101@peta.id	\N	$2y$12$8ZeXICSmqWMEVjOWHhI19uyer7PaL0MBNs9lgWi6TAt57Mui6./dC	\N	\N	2026-06-15 16:46:29	2026-06-15 16:46:29
102	Nabila Saputra	nabilasaputra102@peta.id	\N	$2y$12$6IZZ.OWu44s8twNfQKEDNO/zLWKswTq/bxL5kyHda6p7UC3Usawwm	\N	\N	2026-06-15 16:46:29	2026-06-15 16:46:29
103	Tiara Firmansyah	tiarafirmansyah103@peta.id	\N	$2y$12$NNwNY14jCQTdG8fCtpmCR.mumlVCslA27PbaoyAgI5JmQbwS1zq4.	\N	\N	2026-06-15 16:46:30	2026-06-15 16:46:30
104	Naufal Saputra	naufalsaputra104@peta.id	\N	$2y$12$pTI2hvgtbcxuizs1hQAFl.IQ07ZGzGw7h4P/phfRaevp41RGzFMua	\N	\N	2026-06-15 16:46:30	2026-06-15 16:46:30
105	Yoga Saputra	yogasaputra105@peta.id	\N	$2y$12$H65T6mMYyMGOC7IE1kYgNOT.8rn1/sctlyvAaKSiBqTBXdww3ktJG	\N	\N	2026-06-15 16:46:30	2026-06-15 16:46:30
106	Putri Firmansyah	putrifirmansyah106@peta.id	\N	$2y$12$kzocb3Ehvj.2nYCTZOGZJ.bV9OeN/Ic.6cq7sv/sqWMmqyl3I568i	\N	\N	2026-06-15 16:46:30	2026-06-15 16:46:30
107	Bima Pratama	bimapratama107@peta.id	\N	$2y$12$wJf.gJwuTh7u3IBvpPmWeOtszoKXiJPEstG4foZes3tuIz8IZCovy	\N	\N	2026-06-15 16:46:31	2026-06-15 16:46:31
108	Aulia Nugroho	aulianugroho108@peta.id	\N	$2y$12$G/FP/wCDphfXUlUrVvcC4.JDt0ztz.8UhgCYgDlUDr49u3cj4r936	\N	\N	2026-06-15 16:46:31	2026-06-15 16:46:31
109	Farhan Kurniawan	farhankurniawan109@peta.id	\N	$2y$12$RVlozp04uFZzmSdG.hjdKuxNLO0Oom9j0CSh7ex6Bdsv.HD.3JKs2	\N	\N	2026-06-15 16:46:31	2026-06-15 16:46:31
110	Nabila Ramadhan	nabilaramadhan110@peta.id	\N	$2y$12$Krw4HYF4.Mpl8I4PSQFLnOKQk2I0NlfMnKzjtbXRgCmt/4hwrJaqy	\N	\N	2026-06-15 16:46:31	2026-06-15 16:46:31
111	Fikri Wijaya	fikriwijaya111@peta.id	\N	$2y$12$LmYIZfY1apG6rpeYDTwHq.HkdXhhzIMPD2Q9CHAfVTz1BlKK4k6jG	\N	\N	2026-06-15 16:46:32	2026-06-15 16:46:32
112	Fajar Permana	fajarpermana112@peta.id	\N	$2y$12$EoQq3VOaK/4PmItpMN9nkuEX2/ZXtFiruBJfTpHNZYDGayPRvrK5m	\N	\N	2026-06-15 16:46:32	2026-06-15 16:46:32
113	Putri Hidayat	putrihidayat113@peta.id	\N	$2y$12$DtKvRJzm/HhAUqVArXfOJOpLHtrxSnZkWJwGxQ3d6D7EB6.rA87NW	\N	\N	2026-06-15 16:46:32	2026-06-15 16:46:32
114	Fajar Nugroho	fajarnugroho114@peta.id	\N	$2y$12$N511oCd27nkW.MwXsTW9hOTkbHV/1rsMvX63xsDwHkPnKiOeBPEs2	\N	\N	2026-06-15 16:46:32	2026-06-15 16:46:32
115	Farhan Nugroho	farhannugroho115@peta.id	\N	$2y$12$RUGPwcTJZGX9SJKnFT8PKOUT7iRIwJ7A79vvNrD8L9sz9kuMGJweO	\N	\N	2026-06-15 16:46:32	2026-06-15 16:46:32
116	Aulia Ramadhan	auliaramadhan116@peta.id	\N	$2y$12$sfOP1cnGvVg8yhFHHcqi0OGUNo/Onzy6Zu3/fFvcTUA2sz9uaPUTi	\N	\N	2026-06-15 16:46:33	2026-06-15 16:46:33
117	Akbar Hidayat	akbarhidayat117@peta.id	\N	$2y$12$oVAn/55n415QoRI4C0l0LeOnSfttfSrN4YHHgZ4n2itHwDNs07g2e	\N	\N	2026-06-15 16:46:33	2026-06-15 16:46:33
118	Putri Ramadhan	putriramadhan118@peta.id	\N	$2y$12$8RmdMXFHRcWucoALEqrBRO6kE.Dr.E9B1711WtD6ay.FzQDn.Cx2y	\N	\N	2026-06-15 16:46:33	2026-06-15 16:46:33
119	Farhan Permana	farhanpermana119@peta.id	\N	$2y$12$G.w9NvG8Zc/G19IRA/B4IOrQFy/.Wr2JwC7um1su1uVAcXs16WPPu	\N	\N	2026-06-15 16:46:33	2026-06-15 16:46:33
120	Putri Wijaya	putriwijaya120@peta.id	\N	$2y$12$FBc9WBVwLbFJIkiztyiGzucABw1Dsa5bnSCSHV2l2sg7U3Wd1Ivsy	\N	\N	2026-06-15 16:46:34	2026-06-15 16:46:34
121	Fajar Hidayat	fajarhidayat121@peta.id	\N	$2y$12$cRQ/ZCCfygwOo/CljLXVt.BvLnuxS2Ldva7SQ/HDksx/9h6Ck.e9i	\N	\N	2026-06-15 16:46:34	2026-06-15 16:46:34
122	Rizki Kurniawan	rizkikurniawan122@peta.id	\N	$2y$12$mtb5yzUMDk5jlkvwSuXlDuDwoaVCM08wKY6DakxF6fNtTp.9of6t6	\N	\N	2026-06-15 16:46:34	2026-06-15 16:46:34
123	Raka Wijaya	rakawijaya123@peta.id	\N	$2y$12$v5rz/1mL8WFz6nOMuy/lb.qEU/ySn9ulI5xVRSq73JhKsboHw732C	\N	\N	2026-06-15 16:46:34	2026-06-15 16:46:34
124	Nabila Permana	nabilapermana124@peta.id	\N	$2y$12$xP2kqHOz8TXIPRTOlZZTBeo2YN6zt8CSOivNmCFi667V7kylPWvdS	\N	\N	2026-06-15 16:46:34	2026-06-15 16:46:34
125	Akbar Maulana	akbarmaulana125@peta.id	\N	$2y$12$TqKTTnq8VPo2iCgrHHrBhulXAiklKjGauO8nZAGSJCEM8qnb.WaQu	\N	\N	2026-06-15 16:46:35	2026-06-15 16:46:35
126	Fajar Ramadhan	fajarramadhan126@peta.id	\N	$2y$12$qyQSy5z.hxnlsQsXh9isjOv3ZCL6t.0x0n5ue9VH23FCZQVneQQ.K	\N	\N	2026-06-15 16:46:35	2026-06-15 16:46:35
127	Andi Ramadhan	andiramadhan127@peta.id	\N	$2y$12$/gUwKNACiyLu/7gDhhmKQeRhuUhmxfzd.iBeK5cHgkNWVvak2VF7K	\N	\N	2026-06-15 16:46:35	2026-06-15 16:46:35
128	Rizki Nugroho	peta@dev.id	\N	$2y$12$tTvt0kmThfqgMOSpr0A6jOPMx9KtQLBJU/.aCxK0JP/9iN8wF0gbm	\N	\N	2026-06-15 16:57:33	2026-06-15 16:57:33
129	Artur	peta2@dev.id	\N	$2y$12$K30d1BKhJhVCUgxabuhIXehTMlzBds0L933K3bTdmM89gabkR/aQ2	\N	\N	2026-06-15 17:00:29	2026-06-15 17:00:29
130	Artur	peta3@dev.id	\N	$2y$12$.nx3fhSkp8Deyj0lvcPfcOvsAulXmc8wUBstb8DHpPPpeN4g6ODvi	\N	\N	2026-06-15 17:03:01	2026-06-15 17:03:01
\.


--
-- Name: permissions_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.permissions_id_seq', 5, true);


--
-- Name: personal_access_tokens_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.personal_access_tokens_id_seq', 1, false);


--
-- Name: roles_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.roles_id_seq', 3, true);


--
-- Name: users_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.users_id_seq', 130, true);


--
-- PostgreSQL database dump complete
--

\unrestrict lQfC1r4Ah3WDHZ3c1nU5Vf9Cv1RXjCEwqHafCTVvwJVY908FswqaYoV651CYQjT

