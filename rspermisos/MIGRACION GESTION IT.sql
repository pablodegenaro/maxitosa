USE [AdminElTriunfoDB]
GO
ALTER TABLE SSUSRS
    ADD ProyectoHome INT DEFAULT 0;
GO
/****** Object:  Table [dbo].[RSCORRELPER]    Script Date: 13/1/2023 1:36:29 p. m. ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE TABLE [dbo].[RSCORRELPER](
	[id] [int] IDENTITY(1,1) NOT NULL,
	[FieldName] [varchar](50) NOT NULL,
	[ValueInt] [int] NOT NULL,
 CONSTRAINT [PK_RSCORRELPER] PRIMARY KEY CLUSTERED 
(
	[id] ASC
)WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON, OPTIMIZE_FOR_SEQUENTIAL_KEY = OFF) ON [PRIMARY]
) ON [PRIMARY]
GO
/****** Object:  Table [dbo].[RSDASH]    Script Date: 13/1/2023 1:36:29 p. m. ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE TABLE [dbo].[RSDASH](
	[id] [int] IDENTITY(1,1) NOT NULL,
	[correldash] [int] NOT NULL,
	[dashboard] [varchar](50) NOT NULL,
	[created_at] [datetime] NOT NULL,
	[updated_at] [datetime] NULL,
 CONSTRAINT [PK_RSDASH] PRIMARY KEY CLUSTERED 
(
	[id] ASC
)WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON, OPTIMIZE_FOR_SEQUENTIAL_KEY = OFF) ON [PRIMARY]
) ON [PRIMARY]
GO
/****** Object:  Table [dbo].[RSDASHROLMEDGROUP]    Script Date: 13/1/2023 1:36:29 p. m. ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE TABLE [dbo].[RSDASHROLMEDGROUP](
	[id] [int] IDENTITY(1,1) NOT NULL,
	[correlrol] [int] NOT NULL,
	[correlmedgroup] [int] NOT NULL,
	[correldash] [int] NOT NULL,
	[created_at] [datetime] NOT NULL,
 CONSTRAINT [PK_RSROLMEDGROUPDASH] PRIMARY KEY CLUSTERED 
(
	[id] ASC
)WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON, OPTIMIZE_FOR_SEQUENTIAL_KEY = OFF) ON [PRIMARY]
) ON [PRIMARY]
GO
/****** Object:  Table [dbo].[RSHIST_ADM_ACCESS]    Script Date: 13/1/2023 1:36:29 p. m. ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE TABLE [dbo].[RSHIST_ADM_ACCESS](
	[id] [int] IDENTITY(1,1) NOT NULL,
	[usuario] [varchar](100) NOT NULL,
	[rol] [varchar](50) NOT NULL,
	[modulo_access] [varchar](50) NOT NULL,
	[moduloext_access] [varchar](50) NOT NULL,
	[created_at] [datetime] NOT NULL,
 CONSTRAINT [PK_RSHIST_ADM_ACCESS] PRIMARY KEY CLUSTERED 
(
	[id] ASC
)WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON, OPTIMIZE_FOR_SEQUENTIAL_KEY = OFF) ON [PRIMARY]
) ON [PRIMARY]
GO
/****** Object:  Table [dbo].[RSMENU]    Script Date: 13/1/2023 1:36:29 p. m. ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE TABLE [dbo].[RSMENU](
	[id] [int] IDENTITY(1,1) NOT NULL,
	[correlmed] [int] NOT NULL,
	[nombre] [varchar](50) NOT NULL,
	[menu_orden] [int] NOT NULL,
	[menu_padre] [int] NULL,
	[icono] [varchar](50) NULL,
	[correlmenugroup_fk] [int] NOT NULL,
	[status] [int] NOT NULL,
	[created_at] [datetime] NOT NULL,
 CONSTRAINT [PK_RSMENU] PRIMARY KEY CLUSTERED 
(
	[id] ASC
)WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON, OPTIMIZE_FOR_SEQUENTIAL_KEY = OFF) ON [PRIMARY]
) ON [PRIMARY]
GO
/****** Object:  Table [dbo].[RSMENUGROUP]    Script Date: 13/1/2023 1:36:29 p. m. ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE TABLE [dbo].[RSMENUGROUP](
	[id] [int] IDENTITY(1,1) NOT NULL,
	[correlmedgroup] [int] NOT NULL,
	[identificador_menu] [varchar](20) NOT NULL,
	[nombre] [varchar](50) NOT NULL,
	[tipo_menu] [varchar](1) NOT NULL,
	[correlpro_fk] [int] NOT NULL,
	[status] [int] NOT NULL,
	[created_at] [datetime] NOT NULL,
 CONSTRAINT [PK_RSMENUGROUP] PRIMARY KEY CLUSTERED 
(
	[id] ASC
)WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON, OPTIMIZE_FOR_SEQUENTIAL_KEY = OFF) ON [PRIMARY]
) ON [PRIMARY]
GO
/****** Object:  Table [dbo].[RSMODULO]    Script Date: 13/1/2023 1:36:29 p. m. ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE TABLE [dbo].[RSMODULO](
	[id] [int] IDENTITY(1,1) NOT NULL,
	[correlmod] [int] NOT NULL,
	[nombre] [varchar](50) NOT NULL,
	[icono] [varchar](50) NULL,
	[ruta] [varchar](50) NOT NULL,
	[modulo_orden] [int] NOT NULL,
	[correlmed_fk] [int] NOT NULL,
	[status] [int] NOT NULL,
	[created_at] [datetime] NOT NULL,
	[updated_at] [datetime] NULL,
 CONSTRAINT [PK_RSMODULO] PRIMARY KEY CLUSTERED 
(
	[id] ASC
)WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON, OPTIMIZE_FOR_SEQUENTIAL_KEY = OFF) ON [PRIMARY]
) ON [PRIMARY]
GO
/****** Object:  Table [dbo].[RSMODULOEXT]    Script Date: 13/1/2023 1:36:29 p. m. ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE TABLE [dbo].[RSMODULOEXT](
	[id] [int] IDENTITY(1,1) NOT NULL,
	[correlmodext] [int] NOT NULL,
	[correlmod_fk] [int] NOT NULL,
	[nombre] [varchar](50) NOT NULL,
	[ruta] [varchar](50) NOT NULL,
	[required_adm] [int] NOT NULL,
	[status] [int] NOT NULL,
	[created_at] [datetime] NOT NULL,
 CONSTRAINT [PK_RSMODULOEXT] PRIMARY KEY CLUSTERED 
(
	[id] ASC
)WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON, OPTIMIZE_FOR_SEQUENTIAL_KEY = OFF) ON [PRIMARY]
) ON [PRIMARY]
GO
/****** Object:  Table [dbo].[RSPERMISO]    Script Date: 13/1/2023 1:36:29 p. m. ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE TABLE [dbo].[RSPERMISO](
	[id] [int] IDENTITY(1,1) NOT NULL,
	[correlper] [int] NOT NULL,
	[modulo_id] [int] NOT NULL,
	[usuario_id] [int] NOT NULL,
	[created_at] [datetime] NOT NULL,
 CONSTRAINT [PK_RSPERMISO] PRIMARY KEY CLUSTERED 
(
	[id] ASC
)WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON, OPTIMIZE_FOR_SEQUENTIAL_KEY = OFF) ON [PRIMARY]
) ON [PRIMARY]
GO
/****** Object:  Table [dbo].[RSPERMISOEXT]    Script Date: 13/1/2023 1:36:29 p. m. ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE TABLE [dbo].[RSPERMISOEXT](
	[id] [int] IDENTITY(1,1) NOT NULL,
	[correlmodext] [int] NOT NULL,
	[correlper_fk] [int] NOT NULL,
	[nombre] [varchar](50) NOT NULL,
	[ruta] [varchar](50) NOT NULL,
	[required_adm] [int] NOT NULL,
	[status] [int] NOT NULL,
	[created_at] [datetime] NOT NULL,
 CONSTRAINT [PK_RSPERMISOEXT] PRIMARY KEY CLUSTERED 
(
	[id] ASC
)WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON, OPTIMIZE_FOR_SEQUENTIAL_KEY = OFF) ON [PRIMARY]
) ON [PRIMARY]
GO
/****** Object:  Table [dbo].[RSPROYECTO]    Script Date: 13/1/2023 1:36:29 p. m. ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE TABLE [dbo].[RSPROYECTO](
	[id] [int] IDENTITY(1,1) NOT NULL,
	[correlpro] [int] NOT NULL,
	[nombre] [varchar](50) NOT NULL,
	[principal] [varchar](50) NULL,
	[proyecto_principal] [tinyint] NOT NULL,
	[created_at] [datetime] NOT NULL,
 CONSTRAINT [PK_RSPROYECTO] PRIMARY KEY CLUSTERED 
(
	[id] ASC
)WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON, OPTIMIZE_FOR_SEQUENTIAL_KEY = OFF) ON [PRIMARY]
) ON [PRIMARY]
GO
/****** Object:  Table [dbo].[RSROL]    Script Date: 13/1/2023 1:36:29 p. m. ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE TABLE [dbo].[RSROL](
	[id] [int] IDENTITY(1,1) NOT NULL,
	[correlrol] [int] NOT NULL,
	[nombre] [varchar](50) NOT NULL,
	[es_adm] [int] NOT NULL,
	[created_at] [datetime] NOT NULL,
	[updated_at] [datetime] NULL,
 CONSTRAINT [PK_RSROL] PRIMARY KEY CLUSTERED 
(
	[id] ASC
)WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON, OPTIMIZE_FOR_SEQUENTIAL_KEY = OFF) ON [PRIMARY]
) ON [PRIMARY]
GO
/****** Object:  Table [dbo].[RSROLMOD]    Script Date: 13/1/2023 1:36:29 p. m. ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE TABLE [dbo].[RSROLMOD](
	[id] [int] IDENTITY(1,1) NOT NULL,
	[correlrodmod] [int] NOT NULL,
	[correlrol_fk] [int] NOT NULL,
	[correlmod_fk] [int] NOT NULL,
	[created_at] [datetime] NOT NULL,
 CONSTRAINT [PK_RSROLMOD] PRIMARY KEY CLUSTERED 
(
	[id] ASC
)WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON, OPTIMIZE_FOR_SEQUENTIAL_KEY = OFF) ON [PRIMARY]
) ON [PRIMARY]
GO
/****** Object:  Table [dbo].[RSROLMODEXT]    Script Date: 13/1/2023 1:36:29 p. m. ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE TABLE [dbo].[RSROLMODEXT](
	[id] [int] IDENTITY(1,1) NOT NULL,
	[correlmodext] [int] NOT NULL,
	[correlrolmod_fk] [int] NOT NULL,
	[nombre] [varchar](50) NOT NULL,
	[ruta] [varchar](50) NOT NULL,
	[required_adm] [int] NOT NULL,
	[status] [int] NOT NULL,
	[created_at] [datetime] NOT NULL,
 CONSTRAINT [PK_RSROLMODEXT] PRIMARY KEY CLUSTERED 
(
	[id] ASC
)WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON, OPTIMIZE_FOR_SEQUENTIAL_KEY = OFF) ON [PRIMARY]
) ON [PRIMARY]
GO
ALTER TABLE [dbo].[RSMENUGROUP] ADD  CONSTRAINT [DF_RSMENUGROUP_tipo_menu]  DEFAULT ('V') FOR [tipo_menu]
GO
ALTER TABLE [dbo].[RSMENUGROUP] ADD  CONSTRAINT [DF_RSMENUGROUP_this_main_menu]  DEFAULT ((0)) FOR [correlpro_fk]
GO
ALTER TABLE [dbo].[RSPROYECTO] ADD  CONSTRAINT [DF_RSPROYECTO_proyecto_principal]  DEFAULT ((0)) FOR [proyecto_principal]
GO
EXEC sys.sp_addextendedproperty @name=N'MS_Description', @value=N'este atributo es para definir si es menu horizontal o vertical' , @level0type=N'SCHEMA',@level0name=N'dbo', @level1type=N'TABLE',@level1name=N'RSMENUGROUP', @level2type=N'COLUMN',@level2name=N'tipo_menu'
GO
