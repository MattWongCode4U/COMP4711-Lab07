3 Model Views
---
- By day
- By time
- by class

Common
---
<timetable set="B">
	<day>
		<class code="comp4711" day="3">
			<coursecode></coursecode>
			<day>
			<time>
			<room>
			<instructor>
		</class>
	</day>
	<course>
		...
	</course>

Master.xml
---
<!DOCTYPE ... >
<!ENTITY part1 SYSTEM "part1.xml">
<!ENTITY part2 SYSTEM "part1.xml">
<!ENTITY part3 SYSTEM "part1.xml">
<timetable>
	&part1;
	.....2;
	.....3;
</timetable>
