function timeExceed(t, s) {
	if(t > 9)
		return t + s
	return "0" + t + s;
}
function pastDuration(contestId, y, m, d, hrs, mins, secs) {
	let dur = ""
	if(y != 0) {
		dur = dur + timeExceed(y, "d ") + timeExceed(m, "m ") + timeExceed(d, "d ") + timeExceed(hrs, ":") + timeExceed(mins, ":") + timeExceed(secs, "");
	} else if(m != 0) {
		dur = dur + timeExceed(m, "m ") + timeExceed(d, "d ") + timeExceed(hrs, ":") + timeExceed(mins, ":") + timeExceed(secs, "");
	} else if(d != 0) {
		dur = dur + timeExceed(d, "d ") + timeExceed(hrs, ":") + timeExceed(mins, ":") + timeExceed(secs, "");
	} else if(hrs != 0) {
		dur = dur + timeExceed(hrs, ":") + timeExceed(mins, ":") + timeExceed(secs, "");
	} else {
		dur = dur + timeExceed(mins, ":") + timeExceed(secs, "");
	}
	$("#contestDuration"+contestId).html("<p>" + dur + "</p>");
}