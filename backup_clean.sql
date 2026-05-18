--
-- PostgreSQL database dump
--

\restrict hFHEEWrATQbbE3AAtPVJId7UVwdiFwDRvzbKzOapFfZWlCTcWJ0mynXKSjcIdHt

-- Dumped from database version 18.2
-- Dumped by pg_dump version 18.2

SET statement_timeout = 0;
SET lock_timeout = 0;
SET idle_in_transaction_session_timeout = 0;
SET transaction_timeout = 0;
SET client_encoding = 'UTF8';
SET standard_conforming_strings = on;
SELECT pg_catalog.set_config('search_path', '', false);
SET check_function_bodies = false;
SET xmloption = content;
SET client_min_messages = warning;
SET row_security = off;

SET default_tablespace = '';

SET default_table_access_method = heap;

--
-- Name: categoria_medicamento; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.categoria_medicamento (
    id_categoria integer NOT NULL,
    nombre_categoria character varying(50)
);


--
-- Name: categoria_medicamento_id_categoria_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.categoria_medicamento_id_categoria_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: categoria_medicamento_id_categoria_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.categoria_medicamento_id_categoria_seq OWNED BY public.categoria_medicamento.id_categoria;


--
-- Name: cita; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.cita (
    id_cita integer NOT NULL,
    fecha_cita date,
    hora_cita time without time zone,
    estado character varying(20),
    id_medico integer,
    id_paciente integer,
    id_usuario integer
);


--
-- Name: cita_id_cita_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.cita_id_cita_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: cita_id_cita_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.cita_id_cita_seq OWNED BY public.cita.id_cita;


--
-- Name: compra_abastecimiento; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.compra_abastecimiento (
    id_compra_abastecimiento integer NOT NULL,
    fecha date,
    id_proveedor integer,
    id_usuario integer
);


--
-- Name: compra_abastecimiento_id_compra_abastecimiento_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.compra_abastecimiento_id_compra_abastecimiento_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: compra_abastecimiento_id_compra_abastecimiento_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.compra_abastecimiento_id_compra_abastecimiento_seq OWNED BY public.compra_abastecimiento.id_compra_abastecimiento;


--
-- Name: consulta; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.consulta (
    id_consulta integer NOT NULL,
    diagnostico character varying(200),
    observaciones character varying(200),
    id_cita integer
);


--
-- Name: consulta_id_consulta_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.consulta_id_consulta_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: consulta_id_consulta_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.consulta_id_consulta_seq OWNED BY public.consulta.id_consulta;


--
-- Name: detalle_compra; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.detalle_compra (
    cantidad integer,
    precio_compra numeric(12,2),
    id_lote integer NOT NULL,
    id_compra_abastecimiento integer NOT NULL
);


--
-- Name: detalle_devolucion; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.detalle_devolucion (
    id_devolucion integer NOT NULL,
    id_lote integer NOT NULL,
    cantidad integer
);


--
-- Name: detalle_receta; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.detalle_receta (
    id_detalle_receta integer NOT NULL,
    cantidad integer,
    dosis character varying(50),
    id_receta integer,
    id_medicamento integer
);


--
-- Name: detalle_receta_id_detalle_receta_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.detalle_receta_id_detalle_receta_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: detalle_receta_id_detalle_receta_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.detalle_receta_id_detalle_receta_seq OWNED BY public.detalle_receta.id_detalle_receta;


--
-- Name: detalle_venta; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.detalle_venta (
    id_venta integer NOT NULL,
    id_lote integer NOT NULL,
    cantidad integer,
    subtotal numeric(10,2)
);


--
-- Name: devolucion; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.devolucion (
    id_devolucion integer NOT NULL,
    fecha date,
    motivo character varying(500),
    id_venta integer
);


--
-- Name: devolucion_id_devolucion_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.devolucion_id_devolucion_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: devolucion_id_devolucion_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.devolucion_id_devolucion_seq OWNED BY public.devolucion.id_devolucion;


--
-- Name: examen_laboratorio; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.examen_laboratorio (
    id_examen bigint NOT NULL,
    nombre_examen character varying(160) NOT NULL,
    costo numeric(10,2) DEFAULT '0'::numeric NOT NULL,
    informacion text,
    activo boolean DEFAULT true NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    codigo_examen character varying(20),
    tipo_muestra character varying(120)
);


--
-- Name: examen_laboratorio_id_examen_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.examen_laboratorio_id_examen_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: examen_laboratorio_id_examen_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.examen_laboratorio_id_examen_seq OWNED BY public.examen_laboratorio.id_examen;


--
-- Name: lote; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.lote (
    id_lote integer NOT NULL,
    stock integer,
    fecha_vencimiento date,
    precio_venta numeric(12,2),
    id_medicamento integer
);


--
-- Name: lote_id_lote_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.lote_id_lote_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: lote_id_lote_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.lote_id_lote_seq OWNED BY public.lote.id_lote;


--
-- Name: medicamento; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.medicamento (
    id_medicamento integer NOT NULL,
    nombre character varying(50),
    id_categoria integer,
    presentacion character varying(100),
    concentracion character varying(100),
    via_administracion character varying(80),
    unidad_medida character varying(40),
    codigo_interno character varying(40),
    descripcion character varying(400),
    activo boolean DEFAULT true NOT NULL
);


--
-- Name: medicamento_id_medicamento_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.medicamento_id_medicamento_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: medicamento_id_medicamento_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.medicamento_id_medicamento_seq OWNED BY public.medicamento.id_medicamento;


--
-- Name: medico; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.medico (
    id_medico integer NOT NULL,
    nombre character varying(50),
    apellido character varying(50),
    especialidad character varying(50),
    telefono character varying(20),
    correo character varying(50),
    id_usuario integer
);


--
-- Name: medico_id_medico_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.medico_id_medico_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: medico_id_medico_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.medico_id_medico_seq OWNED BY public.medico.id_medico;


--
-- Name: migrations; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.migrations (
    id integer NOT NULL,
    migration character varying(255) NOT NULL,
    batch integer NOT NULL
);


--
-- Name: migrations_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.migrations_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: migrations_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.migrations_id_seq OWNED BY public.migrations.id;


--
-- Name: paciente; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.paciente (
    id_paciente integer NOT NULL,
    nombre character varying(50),
    apellido character varying(50),
    telefono character varying(20),
    direccion character varying(100),
    correo character varying(50),
    fecha_nacimiento date,
    nit character varying(30),
    genero character varying(20),
    dpi character varying(30)
);


--
-- Name: paciente_examen_laboratorio; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.paciente_examen_laboratorio (
    id_paciente_examen bigint NOT NULL,
    id_paciente bigint NOT NULL,
    id_examen bigint NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    estado character varying(20) DEFAULT 'ingresado'::character varying NOT NULL,
    codigo_solicitud character varying(40)
);


--
-- Name: paciente_examen_laboratorio_id_paciente_examen_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.paciente_examen_laboratorio_id_paciente_examen_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: paciente_examen_laboratorio_id_paciente_examen_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.paciente_examen_laboratorio_id_paciente_examen_seq OWNED BY public.paciente_examen_laboratorio.id_paciente_examen;


--
-- Name: paciente_id_paciente_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.paciente_id_paciente_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: paciente_id_paciente_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.paciente_id_paciente_seq OWNED BY public.paciente.id_paciente;


--
-- Name: password_reset_tokens; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.password_reset_tokens (
    email character varying(255) NOT NULL,
    token character varying(255) NOT NULL,
    created_at timestamp(0) without time zone
);


--
-- Name: proveedor; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.proveedor (
    id_proveedor integer NOT NULL,
    nombre_empresa character varying(50),
    telefono character varying(20),
    correo character varying(50)
);


--
-- Name: proveedor_id_proveedor_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.proveedor_id_proveedor_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: proveedor_id_proveedor_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.proveedor_id_proveedor_seq OWNED BY public.proveedor.id_proveedor;


--
-- Name: receta; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.receta (
    id_receta integer NOT NULL,
    fecha_receta date,
    id_consulta integer
);


--
-- Name: receta_id_receta_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.receta_id_receta_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: receta_id_receta_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.receta_id_receta_seq OWNED BY public.receta.id_receta;


--
-- Name: rol; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.rol (
    id_rol integer NOT NULL,
    nombre_rol character varying(100)
);


--
-- Name: rol_id_rol_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.rol_id_rol_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: rol_id_rol_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.rol_id_rol_seq OWNED BY public.rol.id_rol;


--
-- Name: usuario; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.usuario (
    id_usuario integer NOT NULL,
    nombre_usuario character varying(100),
    correo character varying(150),
    contrasena character varying(255),
    id_rol integer,
    nombres character varying(120),
    apellidos character varying(120),
    activo boolean DEFAULT true NOT NULL,
    password_changed_at timestamp(0) without time zone,
    CONSTRAINT verificar_longitud CHECK ((length((contrasena)::text) > 7))
);


--
-- Name: usuario_api_token; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.usuario_api_token (
    id_usuario_api_token integer NOT NULL,
    id_usuario integer NOT NULL,
    token_hash character varying(64) NOT NULL,
    abilities json,
    last_used_at timestamp(0) without time zone,
    expires_at timestamp(0) without time zone,
    revoked_at timestamp(0) without time zone,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


--
-- Name: usuario_api_token_id_usuario_api_token_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.usuario_api_token_id_usuario_api_token_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: usuario_api_token_id_usuario_api_token_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.usuario_api_token_id_usuario_api_token_seq OWNED BY public.usuario_api_token.id_usuario_api_token;


--
-- Name: usuario_id_usuario_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.usuario_id_usuario_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: usuario_id_usuario_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.usuario_id_usuario_seq OWNED BY public.usuario.id_usuario;


--
-- Name: usuario_modulo_permiso; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.usuario_modulo_permiso (
    id_usuario_modulo_permiso integer NOT NULL,
    id_usuario integer NOT NULL,
    modulo character varying(40) NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


--
-- Name: usuario_modulo_permiso_id_usuario_modulo_permiso_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.usuario_modulo_permiso_id_usuario_modulo_permiso_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: usuario_modulo_permiso_id_usuario_modulo_permiso_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.usuario_modulo_permiso_id_usuario_modulo_permiso_seq OWNED BY public.usuario_modulo_permiso.id_usuario_modulo_permiso;


--
-- Name: venta_farmacia; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.venta_farmacia (
    id_venta integer NOT NULL,
    fecha date,
    id_paciente integer,
    id_usuario integer
);


--
-- Name: venta_farmacia_id_venta_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.venta_farmacia_id_venta_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: venta_farmacia_id_venta_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.venta_farmacia_id_venta_seq OWNED BY public.venta_farmacia.id_venta;


--
-- Name: categoria_medicamento id_categoria; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.categoria_medicamento ALTER COLUMN id_categoria SET DEFAULT nextval('public.categoria_medicamento_id_categoria_seq'::regclass);


--
-- Name: cita id_cita; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.cita ALTER COLUMN id_cita SET DEFAULT nextval('public.cita_id_cita_seq'::regclass);


--
-- Name: compra_abastecimiento id_compra_abastecimiento; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.compra_abastecimiento ALTER COLUMN id_compra_abastecimiento SET DEFAULT nextval('public.compra_abastecimiento_id_compra_abastecimiento_seq'::regclass);


--
-- Name: consulta id_consulta; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.consulta ALTER COLUMN id_consulta SET DEFAULT nextval('public.consulta_id_consulta_seq'::regclass);


--
-- Name: detalle_receta id_detalle_receta; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.detalle_receta ALTER COLUMN id_detalle_receta SET DEFAULT nextval('public.detalle_receta_id_detalle_receta_seq'::regclass);


--
-- Name: devolucion id_devolucion; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.devolucion ALTER COLUMN id_devolucion SET DEFAULT nextval('public.devolucion_id_devolucion_seq'::regclass);


--
-- Name: examen_laboratorio id_examen; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.examen_laboratorio ALTER COLUMN id_examen SET DEFAULT nextval('public.examen_laboratorio_id_examen_seq'::regclass);


--
-- Name: lote id_lote; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.lote ALTER COLUMN id_lote SET DEFAULT nextval('public.lote_id_lote_seq'::regclass);


--
-- Name: medicamento id_medicamento; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.medicamento ALTER COLUMN id_medicamento SET DEFAULT nextval('public.medicamento_id_medicamento_seq'::regclass);


--
-- Name: medico id_medico; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.medico ALTER COLUMN id_medico SET DEFAULT nextval('public.medico_id_medico_seq'::regclass);


--
-- Name: migrations id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.migrations ALTER COLUMN id SET DEFAULT nextval('public.migrations_id_seq'::regclass);


--
-- Name: paciente id_paciente; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.paciente ALTER COLUMN id_paciente SET DEFAULT nextval('public.paciente_id_paciente_seq'::regclass);


--
-- Name: paciente_examen_laboratorio id_paciente_examen; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.paciente_examen_laboratorio ALTER COLUMN id_paciente_examen SET DEFAULT nextval('public.paciente_examen_laboratorio_id_paciente_examen_seq'::regclass);


--
-- Name: proveedor id_proveedor; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.proveedor ALTER COLUMN id_proveedor SET DEFAULT nextval('public.proveedor_id_proveedor_seq'::regclass);


--
-- Name: receta id_receta; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.receta ALTER COLUMN id_receta SET DEFAULT nextval('public.receta_id_receta_seq'::regclass);


--
-- Name: rol id_rol; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.rol ALTER COLUMN id_rol SET DEFAULT nextval('public.rol_id_rol_seq'::regclass);


--
-- Name: usuario id_usuario; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.usuario ALTER COLUMN id_usuario SET DEFAULT nextval('public.usuario_id_usuario_seq'::regclass);


--
-- Name: usuario_api_token id_usuario_api_token; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.usuario_api_token ALTER COLUMN id_usuario_api_token SET DEFAULT nextval('public.usuario_api_token_id_usuario_api_token_seq'::regclass);


--
-- Name: usuario_modulo_permiso id_usuario_modulo_permiso; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.usuario_modulo_permiso ALTER COLUMN id_usuario_modulo_permiso SET DEFAULT nextval('public.usuario_modulo_permiso_id_usuario_modulo_permiso_seq'::regclass);


--
-- Name: venta_farmacia id_venta; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.venta_farmacia ALTER COLUMN id_venta SET DEFAULT nextval('public.venta_farmacia_id_venta_seq'::regclass);


--
-- Data for Name: categoria_medicamento; Type: TABLE DATA; Schema: public; Owner: -
--

COPY public.categoria_medicamento (id_categoria, nombre_categoria) FROM stdin;
1	Analgésico/Antiprético
2	Antiinflamatorio/Analgésico
3	Antibiótico
4	Antidiabético
5	Antihipertensivo
6	Hipolipemiante
7	Diurético
8	Corticosteroide
\.


--
-- Data for Name: cita; Type: TABLE DATA; Schema: public; Owner: -
--

COPY public.cita (id_cita, fecha_cita, hora_cita, estado, id_medico, id_paciente, id_usuario) FROM stdin;
\.


--
-- Data for Name: compra_abastecimiento; Type: TABLE DATA; Schema: public; Owner: -
--

COPY public.compra_abastecimiento (id_compra_abastecimiento, fecha, id_proveedor, id_usuario) FROM stdin;
1	2026-05-06	1	5
2	2026-05-07	1	5
3	2026-05-07	2	5
4	2026-05-08	2	5
5	2026-05-08	1	5
6	2026-05-08	2	5
7	2026-05-08	1	5
8	2026-05-15	1	5
9	2026-05-15	2	5
10	2026-05-15	1	5
11	2026-05-15	1	5
12	2026-05-16	3	5
13	2026-05-16	2	5
\.


--
-- Data for Name: consulta; Type: TABLE DATA; Schema: public; Owner: -
--

COPY public.consulta (id_consulta, diagnostico, observaciones, id_cita) FROM stdin;
\.


--
-- Data for Name: detalle_compra; Type: TABLE DATA; Schema: public; Owner: -
--

COPY public.detalle_compra (cantidad, precio_compra, id_lote, id_compra_abastecimiento) FROM stdin;
150	5.00	1	1
300	5.00	2	2
100	6.00	3	3
200	4.50	4	4
50	12.50	5	4
75	20.00	6	5
50	10.00	7	5
25	12.00	8	6
80	50.00	9	7
100	5.00	10	8
10	5.00	11	9
200	5.50	12	10
60	6.50	13	11
25	20.00	14	12
100	50.00	15	13
\.


--
-- Data for Name: detalle_devolucion; Type: TABLE DATA; Schema: public; Owner: -
--

COPY public.detalle_devolucion (id_devolucion, id_lote, cantidad) FROM stdin;
1	2	50
2	2	25
\.


--
-- Data for Name: detalle_receta; Type: TABLE DATA; Schema: public; Owner: -
--

COPY public.detalle_receta (id_detalle_receta, cantidad, dosis, id_receta, id_medicamento) FROM stdin;
\.


--
-- Data for Name: detalle_venta; Type: TABLE DATA; Schema: public; Owner: -
--

COPY public.detalle_venta (id_venta, id_lote, cantidad, subtotal) FROM stdin;
6	2	50	325.00
7	2	50	325.00
8	8	12	180.00
9	8	8	120.00
10	3	10	80.00
11	4	10	60.00
12	2	20	130.00
13	6	25	625.00
14	15	25	1375.00
\.


--
-- Data for Name: devolucion; Type: TABLE DATA; Schema: public; Owner: -
--

COPY public.devolucion (id_devolucion, fecha, motivo, id_venta) FROM stdin;
1	2026-05-07	Error en la compra	6
2	2026-05-07	Producto Dañado	7
\.


--
-- Data for Name: examen_laboratorio; Type: TABLE DATA; Schema: public; Owner: -
--

COPY public.examen_laboratorio (id_examen, nombre_examen, costo, informacion, activo, created_at, updated_at, codigo_examen, tipo_muestra) FROM stdin;
1	Hemograma completo	120.00	Detección de anemia, infecciones o alteraciones sanguíneas.	t	2026-05-12 23:59:47	2026-05-13 00:02:16	EXA-00001	Sangre
2	Glucosa en sangre	250.00	Control o detección de diabetes	t	2026-05-13 00:29:16	2026-05-13 00:29:16	EXA-00002	Sangre
\.


--
-- Data for Name: lote; Type: TABLE DATA; Schema: public; Owner: -
--

COPY public.lote (id_lote, stock, fecha_vencimiento, precio_venta, id_medicamento) FROM stdin;
1	150	2030-02-28	6.50	3
5	50	2029-10-31	15.00	1
7	50	2027-10-31	12.50	6
9	80	2026-05-16	60.00	12
8	5	2026-06-19	15.00	11
10	100	2035-02-08	7.50	2
11	10	2029-02-28	7.50	5
3	90	2028-10-21	8.00	5
4	190	2032-12-21	6.00	2
2	230	2029-02-20	6.50	3
12	200	2027-01-28	4.50	8
13	60	2029-05-29	7.00	9
14	25	2036-12-28	25.00	7
6	50	2027-04-29	25.00	7
15	75	2029-03-09	55.00	10
\.


--
-- Data for Name: medicamento; Type: TABLE DATA; Schema: public; Owner: -
--

COPY public.medicamento (id_medicamento, nombre, id_categoria, presentacion, concentracion, via_administracion, unidad_medida, codigo_interno, descripcion, activo) FROM stdin;
2	Ibuprofeno	2	Tableta	400 mg	Oral	\N	ID-00002	\N	t
4	Metformina	4	Cápsula	850 mg	Oral	\N	ID-00004	\N	t
5	Diclofenaco	2	Ampolla	75 mg/ 3ml	Intramuscular	\N	ID-00005	\N	t
1	Paracetamol	1	Tableta	500 mg	Oral	\N	ID-00001	\N	t
3	Amoxicilina	3	Cápsula	500 mg	Oral	\N	ID-00003	\N	t
6	Azitromicina	3	Tableta	500 mg	Oral	\N	ID-00006	\N	t
7	Lorsatán	5	Tableta	50 mg	Oral	\N	ID-00007	\N	t
8	Enalapril	5	Tableta	10 mg	Oral	\N	ID-00008	\N	t
9	Simsavastina	6	Tableta	20 mg	Tableta	\N	ID-00009	\N	t
10	Furosemida	7	Tableta	40 mg	Oral	\N	ID-00010	\N	t
11	Prednisona	8	Ampolla	4mg/ml	Intravenosa	\N	ID-00011	\N	t
12	Dexametasona	8	Tableta	4 mg/ml	Ampolla	\N	ID-00012	\N	t
\.


--
-- Data for Name: medico; Type: TABLE DATA; Schema: public; Owner: -
--

COPY public.medico (id_medico, nombre, apellido, especialidad, telefono, correo, id_usuario) FROM stdin;
\.


--
-- Data for Name: migrations; Type: TABLE DATA; Schema: public; Owner: -
--

COPY public.migrations (id, migration, batch) FROM stdin;
1	2026_04_27_000003_add_nombres_apellidos_to_usuario_table	1
2	2026_04_27_000004_add_security_fields_and_permissions_to_usuario	2
3	2026_04_27_000005_expand_usuario_correo_y_contrasena	3
4	2026_04_27_000006_create_usuario_api_tokens_table	4
5	2026_04_27_000007_create_password_reset_tokens_if_missing	4
6	2026_05_06_180000_add_campos_to_medicamento_table	5
7	2026_05_06_190000_add_campos_identificacion_to_paciente_table	6
8	2026_05_07_170000_add_activo_to_medicamento_table	7
9	2026_05_12_180000_create_examen_laboratorio_table	8
10	2026_05_12_181000_add_codigo_examen_to_examen_laboratorio_table	9
11	2026_05_12_200000_create_examen_laboratorio_table	10
12	2026_05_12_210000_add_tipo_muestra_to_examen_laboratorio_table	10
13	2026_05_12_220000_create_paciente_examen_laboratorio_table	10
14	2026_05_12_230000_add_estado_to_paciente_examen_laboratorio_table	10
15	2026_05_12_231000_add_codigo_solicitud_to_paciente_examen_laboratorio_table	11
\.


--
-- Data for Name: paciente; Type: TABLE DATA; Schema: public; Owner: -
--

COPY public.paciente (id_paciente, nombre, apellido, telefono, direccion, correo, fecha_nacimiento, nit, genero, dpi) FROM stdin;
1	Luis David	Ixquiac Sac	56261752	15 Avenidad A 2-37 Zona 1, Quetzaltenango	davidixquiac212@gmail.com	2005-02-28	5267048	Masculino	3142479710901
2	Jessica Sofia	Yllescas Quiroa	56261756	Quetzaltenango 19 avenida 21-30	yesicaqui21@gmail.com	1997-09-19	14348921	Femenino	3356626840901
\.


--
-- Data for Name: paciente_examen_laboratorio; Type: TABLE DATA; Schema: public; Owner: -
--

COPY public.paciente_examen_laboratorio (id_paciente_examen, id_paciente, id_examen, created_at, updated_at, estado, codigo_solicitud) FROM stdin;
6	1	1	2026-05-13 01:04:45	2026-05-13 01:28:17	finalizado	SOL-000005
5	1	2	2026-05-13 01:04:45	2026-05-13 01:31:38	finalizado	SOL-000005
7	1	2	2026-05-13 01:32:13	2026-05-13 01:32:33	finalizado	SOL-000007
9	1	1	2026-05-13 01:32:48	2026-05-13 01:33:00	cancelado	SOL-000008
8	1	2	2026-05-13 01:32:48	2026-05-13 01:33:21	finalizado	SOL-000008
10	2	2	2026-05-13 01:43:38	2026-05-15 01:45:11	finalizado	SOL-000010
\.


--
-- Data for Name: password_reset_tokens; Type: TABLE DATA; Schema: public; Owner: -
--

COPY public.password_reset_tokens (email, token, created_at) FROM stdin;
qa.auth.1777344244@hp.com	$2y$12$tWBeVYDFtPYgdc23s4LRLOrHBvRpp.fYfFX143aQyudb0XHnOxSn.	2026-04-28 02:44:34
qa.reset.1777344292@hp.com	$2y$12$OCXjGx55nXK6s3T4/N2hZuPoXt4FttUeHtngUrEYBI8Y4Wyywc.Zm	2026-04-28 02:44:53
\.


--
-- Data for Name: proveedor; Type: TABLE DATA; Schema: public; Owner: -
--

COPY public.proveedor (id_proveedor, nombre_empresa, telefono, correo) FROM stdin;
1	Distribuidora de Occidente	77615891	OccidenteDistri@gmail.com
2	Distribuidora MediGT	77685834	MediGTDistri@gmail.com
3	Distribuidora La Nacional	77615895	NacionalDistri@gmail.com
\.


--
-- Data for Name: receta; Type: TABLE DATA; Schema: public; Owner: -
--

COPY public.receta (id_receta, fecha_receta, id_consulta) FROM stdin;
\.


--
-- Data for Name: rol; Type: TABLE DATA; Schema: public; Owner: -
--

COPY public.rol (id_rol, nombre_rol) FROM stdin;
1	administracion
2	farmacia
3	laboratorio
4	reportes
\.


--
-- Data for Name: usuario; Type: TABLE DATA; Schema: public; Owner: -
--

COPY public.usuario (id_usuario, nombre_usuario, correo, contrasena, id_rol, nombres, apellidos, activo, password_changed_at) FROM stdin;
5	ldixquiac	davidixquiac212@gmail.com	$2y$12$gpHFHQgXhWkXn./.elzoWeS3WoDXEk8Lt4t5HJTdOUgH5C7DWw1Q.	1	Luis David	Ixquiac Sac	t	2026-05-05 04:36:06
6	damarin	daniela12@gmail.com	$2y$12$mYeTOR7Xt9GAl7zmIwQ7HOUyC8s6B9zYfbTHWOEDIBo.t4yNtAzmm	2	Daniela Alexandra	Marín Orozco	t	2026-05-12 00:02:40
9	dmarin	dana@gmail.com	$2y$12$lW548YnFbTHYiVSSI1.I4OL.iQPVyYMr4e7Vd8UJzMaw5z2nO6edS	2	Daniela	Marín	t	2026-05-16 19:54:30
\.


--
-- Data for Name: usuario_api_token; Type: TABLE DATA; Schema: public; Owner: -
--

COPY public.usuario_api_token (id_usuario_api_token, id_usuario, token_hash, abilities, last_used_at, expires_at, revoked_at, created_at, updated_at) FROM stdin;
\.


--
-- Data for Name: usuario_modulo_permiso; Type: TABLE DATA; Schema: public; Owner: -
--

COPY public.usuario_modulo_permiso (id_usuario_modulo_permiso, id_usuario, modulo, created_at, updated_at) FROM stdin;
\.


--
-- Data for Name: venta_farmacia; Type: TABLE DATA; Schema: public; Owner: -
--

COPY public.venta_farmacia (id_venta, fecha, id_paciente, id_usuario) FROM stdin;
6	2026-05-07	\N	5
7	2026-05-07	\N	5
8	2026-05-08	\N	5
9	2026-05-08	\N	5
10	2026-05-16	\N	5
11	2026-05-15	1	5
12	2026-05-15	1	5
13	2026-05-16	\N	5
14	2026-05-16	1	5
\.


--
-- Name: categoria_medicamento_id_categoria_seq; Type: SEQUENCE SET; Schema: public; Owner: -
--

SELECT pg_catalog.setval('public.categoria_medicamento_id_categoria_seq', 8, true);


--
-- Name: cita_id_cita_seq; Type: SEQUENCE SET; Schema: public; Owner: -
--

SELECT pg_catalog.setval('public.cita_id_cita_seq', 1, false);


--
-- Name: compra_abastecimiento_id_compra_abastecimiento_seq; Type: SEQUENCE SET; Schema: public; Owner: -
--

SELECT pg_catalog.setval('public.compra_abastecimiento_id_compra_abastecimiento_seq', 13, true);


--
-- Name: consulta_id_consulta_seq; Type: SEQUENCE SET; Schema: public; Owner: -
--

SELECT pg_catalog.setval('public.consulta_id_consulta_seq', 1, false);


--
-- Name: detalle_receta_id_detalle_receta_seq; Type: SEQUENCE SET; Schema: public; Owner: -
--

SELECT pg_catalog.setval('public.detalle_receta_id_detalle_receta_seq', 1, false);


--
-- Name: devolucion_id_devolucion_seq; Type: SEQUENCE SET; Schema: public; Owner: -
--

SELECT pg_catalog.setval('public.devolucion_id_devolucion_seq', 2, true);


--
-- Name: examen_laboratorio_id_examen_seq; Type: SEQUENCE SET; Schema: public; Owner: -
--

SELECT pg_catalog.setval('public.examen_laboratorio_id_examen_seq', 2, true);


--
-- Name: lote_id_lote_seq; Type: SEQUENCE SET; Schema: public; Owner: -
--

SELECT pg_catalog.setval('public.lote_id_lote_seq', 15, true);


--
-- Name: medicamento_id_medicamento_seq; Type: SEQUENCE SET; Schema: public; Owner: -
--

SELECT pg_catalog.setval('public.medicamento_id_medicamento_seq', 14, true);


--
-- Name: medico_id_medico_seq; Type: SEQUENCE SET; Schema: public; Owner: -
--

SELECT pg_catalog.setval('public.medico_id_medico_seq', 1, false);


--
-- Name: migrations_id_seq; Type: SEQUENCE SET; Schema: public; Owner: -
--

SELECT pg_catalog.setval('public.migrations_id_seq', 15, true);


--
-- Name: paciente_examen_laboratorio_id_paciente_examen_seq; Type: SEQUENCE SET; Schema: public; Owner: -
--

SELECT pg_catalog.setval('public.paciente_examen_laboratorio_id_paciente_examen_seq', 10, true);


--
-- Name: paciente_id_paciente_seq; Type: SEQUENCE SET; Schema: public; Owner: -
--

SELECT pg_catalog.setval('public.paciente_id_paciente_seq', 2, true);


--
-- Name: proveedor_id_proveedor_seq; Type: SEQUENCE SET; Schema: public; Owner: -
--

SELECT pg_catalog.setval('public.proveedor_id_proveedor_seq', 3, true);


--
-- Name: receta_id_receta_seq; Type: SEQUENCE SET; Schema: public; Owner: -
--

SELECT pg_catalog.setval('public.receta_id_receta_seq', 1, false);


--
-- Name: rol_id_rol_seq; Type: SEQUENCE SET; Schema: public; Owner: -
--

SELECT pg_catalog.setval('public.rol_id_rol_seq', 4, true);


--
-- Name: usuario_api_token_id_usuario_api_token_seq; Type: SEQUENCE SET; Schema: public; Owner: -
--

SELECT pg_catalog.setval('public.usuario_api_token_id_usuario_api_token_seq', 4, true);


--
-- Name: usuario_id_usuario_seq; Type: SEQUENCE SET; Schema: public; Owner: -
--

SELECT pg_catalog.setval('public.usuario_id_usuario_seq', 9, true);


--
-- Name: usuario_modulo_permiso_id_usuario_modulo_permiso_seq; Type: SEQUENCE SET; Schema: public; Owner: -
--

SELECT pg_catalog.setval('public.usuario_modulo_permiso_id_usuario_modulo_permiso_seq', 1, true);


--
-- Name: venta_farmacia_id_venta_seq; Type: SEQUENCE SET; Schema: public; Owner: -
--

SELECT pg_catalog.setval('public.venta_farmacia_id_venta_seq', 14, true);


--
-- Name: categoria_medicamento categoria_medicamento_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.categoria_medicamento
    ADD CONSTRAINT categoria_medicamento_pkey PRIMARY KEY (id_categoria);


--
-- Name: cita cita_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.cita
    ADD CONSTRAINT cita_pkey PRIMARY KEY (id_cita);


--
-- Name: compra_abastecimiento compra_abastecimiento_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.compra_abastecimiento
    ADD CONSTRAINT compra_abastecimiento_pkey PRIMARY KEY (id_compra_abastecimiento);


--
-- Name: consulta consulta_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.consulta
    ADD CONSTRAINT consulta_pkey PRIMARY KEY (id_consulta);


--
-- Name: detalle_compra detalle_compra_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.detalle_compra
    ADD CONSTRAINT detalle_compra_pkey PRIMARY KEY (id_lote, id_compra_abastecimiento);


--
-- Name: detalle_devolucion detalle_devolucion_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.detalle_devolucion
    ADD CONSTRAINT detalle_devolucion_pkey PRIMARY KEY (id_devolucion, id_lote);


--
-- Name: detalle_receta detalle_receta_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.detalle_receta
    ADD CONSTRAINT detalle_receta_pkey PRIMARY KEY (id_detalle_receta);


--
-- Name: detalle_venta detalle_venta_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.detalle_venta
    ADD CONSTRAINT detalle_venta_pkey PRIMARY KEY (id_venta, id_lote);


--
-- Name: devolucion devolucion_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.devolucion
    ADD CONSTRAINT devolucion_pkey PRIMARY KEY (id_devolucion);


--
-- Name: examen_laboratorio examen_laboratorio_codigo_examen_unique; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.examen_laboratorio
    ADD CONSTRAINT examen_laboratorio_codigo_examen_unique UNIQUE (codigo_examen);


--
-- Name: examen_laboratorio examen_laboratorio_nombre_examen_unique; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.examen_laboratorio
    ADD CONSTRAINT examen_laboratorio_nombre_examen_unique UNIQUE (nombre_examen);


--
-- Name: examen_laboratorio examen_laboratorio_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.examen_laboratorio
    ADD CONSTRAINT examen_laboratorio_pkey PRIMARY KEY (id_examen);


--
-- Name: lote lote_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.lote
    ADD CONSTRAINT lote_pkey PRIMARY KEY (id_lote);


--
-- Name: medicamento medicamento_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.medicamento
    ADD CONSTRAINT medicamento_pkey PRIMARY KEY (id_medicamento);


--
-- Name: medico medico_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.medico
    ADD CONSTRAINT medico_pkey PRIMARY KEY (id_medico);


--
-- Name: migrations migrations_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.migrations
    ADD CONSTRAINT migrations_pkey PRIMARY KEY (id);


--
-- Name: paciente_examen_laboratorio paciente_examen_laboratorio_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.paciente_examen_laboratorio
    ADD CONSTRAINT paciente_examen_laboratorio_pkey PRIMARY KEY (id_paciente_examen);


--
-- Name: paciente paciente_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.paciente
    ADD CONSTRAINT paciente_pkey PRIMARY KEY (id_paciente);


--
-- Name: password_reset_tokens password_reset_tokens_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.password_reset_tokens
    ADD CONSTRAINT password_reset_tokens_pkey PRIMARY KEY (email);


--
-- Name: proveedor proveedor_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.proveedor
    ADD CONSTRAINT proveedor_pkey PRIMARY KEY (id_proveedor);


--
-- Name: receta receta_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.receta
    ADD CONSTRAINT receta_pkey PRIMARY KEY (id_receta);


--
-- Name: rol rol_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.rol
    ADD CONSTRAINT rol_pkey PRIMARY KEY (id_rol);


--
-- Name: usuario_api_token usuario_api_token_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.usuario_api_token
    ADD CONSTRAINT usuario_api_token_pkey PRIMARY KEY (id_usuario_api_token);


--
-- Name: usuario_api_token usuario_api_token_token_hash_unique; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.usuario_api_token
    ADD CONSTRAINT usuario_api_token_token_hash_unique UNIQUE (token_hash);


--
-- Name: usuario_modulo_permiso usuario_modulo_permiso_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.usuario_modulo_permiso
    ADD CONSTRAINT usuario_modulo_permiso_pkey PRIMARY KEY (id_usuario_modulo_permiso);


--
-- Name: usuario_modulo_permiso usuario_modulo_permiso_unique; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.usuario_modulo_permiso
    ADD CONSTRAINT usuario_modulo_permiso_unique UNIQUE (id_usuario, modulo);


--
-- Name: usuario usuario_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.usuario
    ADD CONSTRAINT usuario_pkey PRIMARY KEY (id_usuario);


--
-- Name: venta_farmacia venta_farmacia_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.venta_farmacia
    ADD CONSTRAINT venta_farmacia_pkey PRIMARY KEY (id_venta);


--
-- Name: paciente_examen_laboratorio_codigo_solicitud_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX paciente_examen_laboratorio_codigo_solicitud_index ON public.paciente_examen_laboratorio USING btree (codigo_solicitud);


--
-- Name: paciente_examen_laboratorio_estado_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX paciente_examen_laboratorio_estado_index ON public.paciente_examen_laboratorio USING btree (estado);


--
-- Name: paciente_examen_laboratorio_id_examen_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX paciente_examen_laboratorio_id_examen_index ON public.paciente_examen_laboratorio USING btree (id_examen);


--
-- Name: paciente_examen_laboratorio_id_paciente_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX paciente_examen_laboratorio_id_paciente_index ON public.paciente_examen_laboratorio USING btree (id_paciente);


--
-- Name: cita cita_id_medico_fkey; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.cita
    ADD CONSTRAINT cita_id_medico_fkey FOREIGN KEY (id_medico) REFERENCES public.medico(id_medico);


--
-- Name: cita cita_id_paciente_fkey; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.cita
    ADD CONSTRAINT cita_id_paciente_fkey FOREIGN KEY (id_paciente) REFERENCES public.paciente(id_paciente);


--
-- Name: cita cita_id_usuario_fkey; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.cita
    ADD CONSTRAINT cita_id_usuario_fkey FOREIGN KEY (id_usuario) REFERENCES public.usuario(id_usuario);


--
-- Name: compra_abastecimiento compra_abastecimiento_id_proveedor_fkey; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.compra_abastecimiento
    ADD CONSTRAINT compra_abastecimiento_id_proveedor_fkey FOREIGN KEY (id_proveedor) REFERENCES public.proveedor(id_proveedor);


--
-- Name: compra_abastecimiento compra_abastecimiento_id_usuario_fkey; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.compra_abastecimiento
    ADD CONSTRAINT compra_abastecimiento_id_usuario_fkey FOREIGN KEY (id_usuario) REFERENCES public.usuario(id_usuario);


--
-- Name: consulta consulta_id_cita_fkey; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.consulta
    ADD CONSTRAINT consulta_id_cita_fkey FOREIGN KEY (id_cita) REFERENCES public.cita(id_cita);


--
-- Name: detalle_compra detalle_compra_id_compra_abastecimiento_fkey; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.detalle_compra
    ADD CONSTRAINT detalle_compra_id_compra_abastecimiento_fkey FOREIGN KEY (id_compra_abastecimiento) REFERENCES public.compra_abastecimiento(id_compra_abastecimiento);


--
-- Name: detalle_compra detalle_compra_id_lote_fkey; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.detalle_compra
    ADD CONSTRAINT detalle_compra_id_lote_fkey FOREIGN KEY (id_lote) REFERENCES public.lote(id_lote);


--
-- Name: detalle_devolucion detalle_devolucion_id_devolucion_fkey; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.detalle_devolucion
    ADD CONSTRAINT detalle_devolucion_id_devolucion_fkey FOREIGN KEY (id_devolucion) REFERENCES public.devolucion(id_devolucion);


--
-- Name: detalle_devolucion detalle_devolucion_id_lote_fkey; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.detalle_devolucion
    ADD CONSTRAINT detalle_devolucion_id_lote_fkey FOREIGN KEY (id_lote) REFERENCES public.lote(id_lote);


--
-- Name: detalle_receta detalle_receta_id_medicamento_fkey; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.detalle_receta
    ADD CONSTRAINT detalle_receta_id_medicamento_fkey FOREIGN KEY (id_medicamento) REFERENCES public.medicamento(id_medicamento);


--
-- Name: detalle_receta detalle_receta_id_receta_fkey; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.detalle_receta
    ADD CONSTRAINT detalle_receta_id_receta_fkey FOREIGN KEY (id_receta) REFERENCES public.receta(id_receta);


--
-- Name: detalle_venta detalle_venta_id_lote_fkey; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.detalle_venta
    ADD CONSTRAINT detalle_venta_id_lote_fkey FOREIGN KEY (id_lote) REFERENCES public.lote(id_lote);


--
-- Name: detalle_venta detalle_venta_id_venta_fkey; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.detalle_venta
    ADD CONSTRAINT detalle_venta_id_venta_fkey FOREIGN KEY (id_venta) REFERENCES public.venta_farmacia(id_venta);


--
-- Name: devolucion devolucion_id_venta_fkey; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.devolucion
    ADD CONSTRAINT devolucion_id_venta_fkey FOREIGN KEY (id_venta) REFERENCES public.venta_farmacia(id_venta);


--
-- Name: lote lote_id_medicamento_fkey; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.lote
    ADD CONSTRAINT lote_id_medicamento_fkey FOREIGN KEY (id_medicamento) REFERENCES public.medicamento(id_medicamento);


--
-- Name: medicamento medicamento_id_categoria_fkey; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.medicamento
    ADD CONSTRAINT medicamento_id_categoria_fkey FOREIGN KEY (id_categoria) REFERENCES public.categoria_medicamento(id_categoria);


--
-- Name: medico medico_id_usuario_fkey; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.medico
    ADD CONSTRAINT medico_id_usuario_fkey FOREIGN KEY (id_usuario) REFERENCES public.usuario(id_usuario);


--
-- Name: paciente_examen_laboratorio paciente_examen_laboratorio_id_examen_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.paciente_examen_laboratorio
    ADD CONSTRAINT paciente_examen_laboratorio_id_examen_foreign FOREIGN KEY (id_examen) REFERENCES public.examen_laboratorio(id_examen) ON DELETE RESTRICT;


--
-- Name: paciente_examen_laboratorio paciente_examen_laboratorio_id_paciente_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.paciente_examen_laboratorio
    ADD CONSTRAINT paciente_examen_laboratorio_id_paciente_foreign FOREIGN KEY (id_paciente) REFERENCES public.paciente(id_paciente) ON DELETE CASCADE;


--
-- Name: receta receta_id_consulta_fkey; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.receta
    ADD CONSTRAINT receta_id_consulta_fkey FOREIGN KEY (id_consulta) REFERENCES public.consulta(id_consulta);


--
-- Name: usuario_api_token usuario_api_token_id_usuario_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.usuario_api_token
    ADD CONSTRAINT usuario_api_token_id_usuario_foreign FOREIGN KEY (id_usuario) REFERENCES public.usuario(id_usuario) ON DELETE CASCADE;


--
-- Name: usuario usuario_id_rol_fkey; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.usuario
    ADD CONSTRAINT usuario_id_rol_fkey FOREIGN KEY (id_rol) REFERENCES public.rol(id_rol);


--
-- Name: usuario_modulo_permiso usuario_modulo_permiso_id_usuario_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.usuario_modulo_permiso
    ADD CONSTRAINT usuario_modulo_permiso_id_usuario_foreign FOREIGN KEY (id_usuario) REFERENCES public.usuario(id_usuario) ON DELETE CASCADE;


--
-- Name: venta_farmacia venta_farmacia_id_paciente_fkey; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.venta_farmacia
    ADD CONSTRAINT venta_farmacia_id_paciente_fkey FOREIGN KEY (id_paciente) REFERENCES public.paciente(id_paciente);


--
-- Name: venta_farmacia venta_farmacia_id_usuario_fkey; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.venta_farmacia
    ADD CONSTRAINT venta_farmacia_id_usuario_fkey FOREIGN KEY (id_usuario) REFERENCES public.usuario(id_usuario);


--
-- PostgreSQL database dump complete
--

\unrestrict hFHEEWrATQbbE3AAtPVJId7UVwdiFwDRvzbKzOapFfZWlCTcWJ0mynXKSjcIdHt

