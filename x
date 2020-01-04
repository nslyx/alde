#!/usr/bin/env bash

# Script 所在目录
DIR=$(cd `dirname $0`;pwd)

# 进入所在的目录
cd ${DIR}

# 创建增量内容目录
WTD='records';
if [[ ! -d ${WTD} ]]; then
 mkdir -p ${WTD}
fi

# 创建增量文件
WTF=`date +'%Y%m'`
if [[ ! -f ${WTD}/${WTF} ]]; then
 touch ${WTD}/${WTF}
fi

# 增量信息
ctm=`date +'%Y-%m-%d %H:%M:%S'`
str="[${ctm}]"
# 追加入文件
echo ${str} >> ${WTD}/${WTF}

GL=''
export GL

# Git 提交
git add .
git commit -m "Submit[${ctm}]" | xargs -I "{}" GL=${GL}"\n{}"  
git pull | xargs -I "{}" GL=${GL}"\n{}"
git push -f | xargs -I "{}" GL=${GL}"\n{}"

# 信息保存，下次提交
echo ${GL} >> ${WTD}/${WTF} 

