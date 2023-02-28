USE [AdminElTriunfoDB]
GO

/****** Object:  Table [dbo].[TLOPER]    Script Date: 19/1/2023 11:00:35 a. m. ******/
SET ANSI_NULLS ON
GO

SET QUOTED_IDENTIFIER ON
GO

CREATE TABLE [dbo].[TLOPER](
	[id] [int] IDENTITY(1,1) NOT NULL,
	[usuario] [varchar](20) NOT NULL,
	[cedula] [int] NOT NULL,
	[descripcion] [varchar](120) NOT NULL,
	[estatus] [int] NOT NULL,
	[created_at] [datetime] NOT NULL,
	[deleted_at] [datetime] NULL,
 CONSTRAINT [PK_TLOPER] PRIMARY KEY CLUSTERED 
(
	[id] ASC
)WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON, OPTIMIZE_FOR_SEQUENTIAL_KEY = OFF) ON [PRIMARY]
) ON [PRIMARY]
GO