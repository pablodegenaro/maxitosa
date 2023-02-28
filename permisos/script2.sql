USE [AdminElTriunfoDB]
GO
ALTER TABLE Menu
    ADD codemenu INT DEFAULT 1;
GO
UPDATE MENU SET codemenu=1
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
ALTER TABLE [dbo].[RSPROYECTO] ADD  CONSTRAINT [DF_RSPROYECTO_proyecto_principal]  DEFAULT ((0)) FOR [proyecto_principal]
GO
INSERT INTO [dbo].[RSPROYECTO]
           ([correlpro]
           ,[nombre]
           ,[principal]
           ,[proyecto_principal]
           ,[created_at])
     VALUES
           (1--<correlpro, int,>
           ,'Principal'--<nombre, varchar(50),>
           ,NULL--<principal, varchar(50),>
           ,1--<proyecto_principal, tinyint,>
           ,'2023-01-18 16:55:00.000'--<created_at, datetime,>
           )
GO
INSERT INTO [dbo].[RSPROYECTO]
           ([correlpro]
           ,[nombre]
           ,[principal]
           ,[proyecto_principal]
           ,[created_at])
     VALUES
           (2--<correlpro, int,>
           ,'Taller'--<nombre, varchar(50),>
           ,NULL--<principal, varchar(50),>
           ,0--<proyecto_principal, tinyint,>
           ,'2023-01-18 16:56:00.000'--<created_at, datetime,>
           )
GO