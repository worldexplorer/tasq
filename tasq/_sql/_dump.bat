@rem mysqldump --add-drop-table -ec -u tasq --password=tasq tasq > 0804-tasq.dump
@rem mysqldump --compatible=mysql40 -K --skip-add-locks --no-set-names --skip-disable-keys --set-charset --add-drop-table -ec -u suzdal -psuzdal suzdal szd_icwhose szd_ic > 0528-suzdal-icwhose-ic.dump

@rem mysqldump --compatible=mysql40 -K --skip-add-locks --no-set-names --skip-disable-keys --set-charset --add-drop-table -ec -u tasq -ptasq tasq > 0530-tasq.dump

@rem mysqldump --compatible=mysql40 -K --skip-add-locks --no-set-names --skip-disable-keys --set-charset --default-character-set=utf8 --skip-disable-keys --add-drop-table -ec -u tasq -ptasq tasq > 0131-tasq.dump
D:\_suitcase\projects\ecintra\mysql\bin\mysqldump --add-drop-table -ec -u tasq -ptasq tasq > 0131-tasq.dump

pause