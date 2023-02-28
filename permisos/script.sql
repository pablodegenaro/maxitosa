UPDATE [dbo].[SADEPO]
SET [Clase] = '00000'
WHERE Descrip like '%PZO'
    GO
UPDATE [dbo].[SADEPO]
SET [Clase] = '00001'
WHERE Descrip like '%MATURIN'
    GO
UPDATE [dbo].[SADEPO]
SET [Clase] = '00002'
WHERE Descrip like '%CARUPANO'
    GO
alter table [dbo].[SAPROD_99]
    add Cubicaje int not null default 0
go

INSERT INTO [dbo].[SACONF]
([CodSucu]
,[Descrip]
,[Direc1]
,[Direc2]
,[Email]
,[Telef]
,[RIF]
,[NroSerial]
,[KeySerial]
,[FechaUV]
,[ZipCode]
,[Municipio]
,[Ciudad]
,[Estado]
,[Pais]
,[SPais]
,[SEstado]
,[SCiudad]
,[SMunicipio]
,[CodTaxs]
,[CostoMes]
,[CorrelPrd]
,[IdAppNot]
,[MesCurso]
,[MesTran]
,[MesOC]
,[MesPrf]
,[IdVersion]
,[IdVerXtra]
,[MesesPtos]
,[ValorPtos]
,[ValorPtosV]
,[FechaUC]
,[MtoExtraG]
,[ImpFleteV]
,[ImpFleteC]
,[RetenIVA]
,[AutoReten]
,[PorctReten]
,[PedirNCtrl]
,[Redond]
,[RedTotal]
,[ObliOper]
,[PaswLim]
,[Adicional]
,[Factor]
,[FactorM]
,[UsaFactorM]
,[SimbFac]
,[EsMoneda]
,[CorrelUNC]
,[Imagen]
,[TipoFac]
,[NroEstable]
,[RUCUser]
,[RUCPwd]
,[CorrelEstac]
,[IMailHost]
,[IMailPort]
,[IMailUser]
,[IMailPwd]
,[IMailSender]
,[TokenEmpresa]
,[TokenSecuencial]
,[FacWSrvURL]
,[TipoAtr]
,[CodTaxD1]
,[CodTaxD2]
,[MtoTaxD1]
,[MtoTaxD2]
,[DctoAplica]
,[AutSRIReten]
,[EstablReten]
,[FecCadReten]
,[PtoEmiReten]
,[NIT]
,[FechaUF]) SELECT '00002'
                 ,Descrip
                 ,Direc1
                 ,Direc2
                 ,Email
                 ,Telef
                 ,RIF
                 ,NroSerial
                 ,KeySerial
                 ,FechaUV
                 ,ZipCode
                 ,Municipio
                 ,Ciudad
                 ,Estado
                 ,Pais
                 ,SPais
                 ,SEstado
                 ,SCiudad
                 ,SMunicipio
                 ,CodTaxs
                 ,CostoMes
                 ,CorrelPrd
                 ,IdAppNot
                 ,MesCurso
                 ,MesTran
                 ,MesOC
                 ,MesPrf
                 ,IdVersion
                 ,IdVerXtra
                 ,MesesPtos
                 ,ValorPtos
                 ,ValorPtosV
                 ,FechaUC
                 ,MtoExtraG
                 ,ImpFleteV
                 ,ImpFleteC
                 ,RetenIVA
                 ,AutoReten
                 ,PorctReten
                 ,PedirNCtrl
                 ,Redond
                 ,RedTotal
                 ,ObliOper
                 ,PaswLim
                 ,Adicional
                 ,Factor
                 ,FactorM
                 ,UsaFactorM
                 ,SimbFac
                 ,EsMoneda
                 ,CorrelUNC
                 ,Imagen
                 ,TipoFac
                 ,NroEstable
                 ,RUCUser
                 ,RUCPwd
                 ,CorrelEstac
                 ,IMailHost
                 ,IMailPort
                 ,IMailUser
                 ,IMailPwd
                 ,IMailSender
                 ,TokenEmpresa
                 ,TokenSecuencial
                 ,FacWSrvURL
                 ,TipoAtr
                 ,CodTaxD1
                 ,CodTaxD2
                 ,MtoTaxD1
                 ,MtoTaxD2
                 ,DctoAplica
                 ,AutSRIReten
                 ,EstablReten
                 ,FecCadReten
                 ,PtoEmiReten
                 ,NIT
                 ,FechaUF FROM SACONF WHERE CodSucu = '00001'
GO



CREATE TABLE [dbo].[Kpi_objetivos](
                                      [id] [int] IDENTITY(1,1) NOT NULL,
                                      [descripcion] [varchar](100) NOT NULL,
                                      CONSTRAINT [PK_Kpi_objetivos] PRIMARY KEY CLUSTERED
                                          (
                                           [id] ASC
                                              )WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON, OPTIMIZE_FOR_SEQUENTIAL_KEY = OFF) ON [PRIMARY]
) ON [PRIMARY]
GO
SET IDENTITY_INSERT [dbo].[Kpi_objetivos] ON
GO
INSERT [dbo].[Kpi_objetivos] ([id], [descripcion]) VALUES (1, N'KG')
GO
INSERT [dbo].[Kpi_objetivos] ([id], [descripcion]) VALUES (2, N'BULTOS')
GO
INSERT [dbo].[Kpi_objetivos] ([id], [descripcion]) VALUES (3, N'UNIDAD')
GO
SET IDENTITY_INSERT [dbo].[Kpi_objetivos] OFF
GO



/****** Object:  Table [dbo].[Doc_App]    Script Date: 28/6/2022 10:14:45 a. m. ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE TABLE [dbo].[Doc_App](
	[id] [int] IDENTITY(1,1) NOT NULL,
	[idDoc] [varchar](50) NOT NULL,
	[NombreDoc] [varchar](100) NULL,
	[CodBanc] [varchar](15) NOT NULL,
	[Descrip] [varchar](50) NULL,
	[NroCta] [varchar](50) NULL,
	[FechaE] [datetime] NOT NULL,
	[Usua] [varchar](50) NOT NULL,
	[CodEsta] [varchar](50) NULL,
	[Procesado] [int] NOT NULL,
CONSTRAINT [PK_Doc_App] PRIMARY KEY CLUSTERED 
(
	[id] ASC
)WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON, OPTIMIZE_FOR_SEQUENTIAL_KEY = OFF) ON [PRIMARY]
) ON [PRIMARY]
GO
/****** Object:  Table [dbo].[Docitem_App]    Script Date: 28/6/2022 10:14:45 a. m. ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE TABLE [dbo].[Docitem_App](
	[id] [int] IDENTITY(1,1) NOT NULL,
	[idDoc] [varchar](50) NOT NULL,
	[NomperBanc] [varchar](50) NULL,
	[FechaE] [datetime] NOT NULL,
	[Concepto] [varchar](200) NOT NULL,
	[Debito] [numeric](18, 2) NOT NULL,
	[Credito] [numeric](18, 2) NOT NULL,
	[Saldo] [numeric](18, 2) NOT NULL,
	[TipoTrans] [varchar](50) NULL,
	[CodTrans] [varchar](10) NULL,
	[Refere] [varchar](50) NULL,
	[Refere2] [varchar](50) NULL,
	[Oficina] [varchar](50) NULL,
	[Procesado] [int] NOT NULL,
CONSTRAINT [PK_Docitem_APp] PRIMARY KEY CLUSTERED 
(
	[id] ASC
)WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON, OPTIMIZE_FOR_SEQUENTIAL_KEY = OFF) ON [PRIMARY]
) ON [PRIMARY]
GO
ALTER TABLE [dbo].[Docitem_App] ADD  CONSTRAINT [DF_Docitem_App_Procesado]  DEFAULT ((0)) FOR [Procesado]
GO





DROP TABLE IF EXISTS [dbo].[app_relacion_cobros]
GO
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE TABLE [dbo].[app_relacion_cobros](
	[id] [int] IDENTITY(1,1) NOT NULL,
	[NroRelacion] [int] NOT NULL,
	[CodUsua] [varchar](10) NOT NULL,
	[CodVend] [varchar](10) NOT NULL,
	[CodSucu] [varchar](5) NOT NULL,
	[FechaE] [datetime] NOT NULL
) ON [PRIMARY]
GO
/****** Object:  Table [dbo].[app_relacion_cobros_items]    Script Date: 4/7/2022 5:20:40 p. m. ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE TABLE [dbo].[app_relacion_cobros_items](
	[id_relacion] [int] NOT NULL,
	[numerod] [varchar](20) NULL,
	[codclie] [varchar](15) NULL,
	[rsocial] [varchar](60) NULL,
	[emision] [date] NULL,
	[vencimiento] [date] NULL,
	[monto] [decimal](28, 2) NULL,
	[vendedor] [varchar](10) NULL,
	[codsucu] [varchar](5) NULL,
	[tipofac] [varchar](1) NULL
) ON [PRIMARY]
GO
