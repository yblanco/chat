--DROP SCHEMA registro CASCADE;
CREATE SCHEMA registro
  AUTHORIZATION chat;

GRANT ALL ON SCHEMA registro TO postgres;
GRANT ALL ON SCHEMA registro TO chat;
COMMENT ON SCHEMA registro
  IS 'Datos del Registro';

CREATE TABLE registro.usuario(
	pk_usu SERIAL NOT NULL,
	ch_nomb CHARACTER VARYING(50) NOT NULL,
	ch_ape CHARACTER VARYING(50),
	ch_use CHARACTER VARYING (20) NOT NULL,
	ch_pass CHARACTER VARYING(32) NOT NULL,
	bo_vis boolean DEFAULT true,
	ch_ipr CHARACTER VARYING(15) NOT NULL,
	ch_ipl CHARACTER VARYING(15) NOT NULL,
	dt_lal TIMESTAMP WITH TIME ZONE DEFAULT now(),
	CONSTRAINT pk_usuario PRIMARY KEY (pk_usu),
	CONSTRAINT usuario_user UNIQUE (ch_use)
);
ALTER TABLE registro.usuario OWNER to chat;

INSERT INTO registro.usuario
	(ch_nomb, ch_ape, ch_use, ch_pass,ch_ipr,ch_ipl)
		VALUES ('Administrador', 'Master', 'admin', '81f681329dd33462326296cb146ee4bd','172.0.0.1','172.0.0.1');

