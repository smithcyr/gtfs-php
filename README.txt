GTFS builder for Coralville, Iowa City, and University of Iowa.

Mostly php-based with some grunt tasks for some file manipulation.

Status:

ROUTE       SHAPE   DATA

Coralville
10thst      X       X
amexpress   X       X
amnorthlib  X       X
lantern     X       X
nightsat    X       X
pmexpress   X       X
pmnorthlib  X       X
pmspecial   X       X

Iowa City
manville    X       X

University of Iowa
red         X       X


Notes:
There are errors in the North Liberty routes- descrepancies between the
information in NextBus/Bongo and the information provided by Coralville Transit.
When the data is corrected in NextBus, stop_id 5201 must be changed to 5203 in
pmnorthlib and stop_id 5221 to 5223.

PM Special runs when Iowa City Schools are in session.
See http://www.iowacityschools.org/pages/ICCSD/District_Overview/7820225131850230500 for updates.

No exeptions have been added yet, i.e. spring break.
