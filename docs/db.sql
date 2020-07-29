CREATE TABLE public.logs_errors
(
  id integer NOT NULL DEFAULT nextval('logs_errors_id_seq'::regclass),
  message character varying NOT NULL,
  creation_date timestamp without time zone NOT NULL DEFAULT now()
)
WITH (
  OIDS=FALSE
);

CREATE TABLE public.tb_conexaophp
(
  id integer NOT NULL DEFAULT nextval('tb_conexaophp_id_seq'::regclass),
  nome character varying NOT NULL
)
WITH (
  OIDS=FALSE
);