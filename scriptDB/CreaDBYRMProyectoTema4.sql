CREATE DATABASE IF NOT EXISTS DBYRMDWESProyectoTema4;
USE DBYRMDWESProyectoTema4;

CREATE TABLE IF NOT EXISTS T02_Departamento (
    T02_CodDepartamento CHAR(3) NOT NULL PRIMARY KEY,
    T02_DescDepartamento VARCHAR(255) NOT NULL,
    T02_FechaCreacionDepartamento DATETIME NOT NULL,
    T02_VolumenDeNegocio FLOAT NOT NULL,
    T02_FechaBajaDepartamento DATETIME NULL
     
);

CREATE USER 'userYRMDWESProyectoTema4'@'%' IDENTIFIED BY 'paso';
GRANT ALL ON DBYRMDWESProyectoTema4.* to 'userYRMDWESProyectoTema4'@'%';