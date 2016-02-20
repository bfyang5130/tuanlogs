drop table if exists  `TraceLog_day`;
CREATE TABLE `TraceLog_day` (
   `Id` int  NOT NULL auto_increment primary key COMMENT '主键',
   `ApplicationId` varchar(64) DEFAULT NULL COMMENT '应用Id',
   `Number` int DEFAULT 0 COMMENT '数量',
   `Date`  int   DEFAULT null COMMENT '统计日期',
   `Updatetime` int   DEFAULT null COMMENT '更新时间',
   KEY (`Date`),
   key(`ApplicationId`)
 ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='跟踪日志表日统计';
 
 drop table if exists  `ErrorLog_day`;
 CREATE TABLE `ErrorLog_day` (
   `Id` int  NOT NULL auto_increment  primary key COMMENT '主键',
   `ApplicationId` varchar(64) DEFAULT NULL COMMENT '应用Id',
   `Number` int DEFAULT 0 COMMENT '数量',
   `Date`  int   DEFAULT null COMMENT '统计日期',
   `Updatetime` int   DEFAULT null COMMENT '更新时间',
   KEY (`Date`),
   key(`ApplicationId`)
 ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='错误日志表日统计';
 
 drop table if exists  `TraceLog_month`;
 CREATE TABLE `TraceLog_month` (
   `Id` int  NOT NULL auto_increment primary key COMMENT '主键',
   `ApplicationId` varchar(64) DEFAULT NULL COMMENT '应用Id',
   `Number` int DEFAULT 0 COMMENT '数量',
   `Month`  int   DEFAULT null COMMENT '统计月份',
   `Updatetime` int   DEFAULT null COMMENT '更新时间',
   KEY (`Month`),
   key(`ApplicationId`)
 ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='跟踪日志表月统计';
 
  drop table if exists  `ErrorLog_month`;
 CREATE TABLE `ErrorLog_month` (
   `Id` int  NOT NULL auto_increment  primary key COMMENT '主键',
   `ApplicationId` varchar(64) DEFAULT NULL COMMENT '应用Id',
   `Number` int DEFAULT 0 COMMENT '数量',
   `Month`  int   DEFAULT null COMMENT '统计月份',
   `Updatetime` int   DEFAULT null COMMENT '更新时间',
   KEY (`Month`),
   key(`ApplicationId`)
 ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='错误日志表月统计';
 
 