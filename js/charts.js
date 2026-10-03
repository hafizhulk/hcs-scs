function co(xl,yl,numericX=false){
  return {responsive:true,maintainAspectRatio:true,plugins:{legend:{labels:{color:'#68766d',font:{family:'IBM Plex Mono',size:11},boxWidth:14}}},scales:{x:{type:numericX?'linear':'category',grid:{color:'rgba(120,110,80,.18)'},ticks:{color:'#68766d',font:{family:'IBM Plex Mono',size:10}},title:{display:true,text:xl,color:'#68766d',font:{family:'IBM Plex Mono',size:11}}},y:{grid:{color:'rgba(120,110,80,.18)'},ticks:{color:'#68766d',font:{family:'IBM Plex Mono',size:10}},title:{display:true,text:yl,color:'#68766d',font:{family:'IBM Plex Mono',size:11}}}}};
}

function replaceChart(previous,id,config){
  if(previous)previous.destroy();
  if(typeof Chart!=='function')return null;
  return new Chart(document.getElementById(id).getContext('2d'),config);
}

function clearChart(chart){if(chart)chart.destroy();return null;}
