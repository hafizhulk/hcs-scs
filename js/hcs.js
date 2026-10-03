let hC=null;
const KM_PER_MILE=1.609344;

let hcsUnitMil=false;

function toggleHCSUnit(){
  const x=gv('hx'),b=gv('hb');
  hcsUnitMil=!hcsUnitMil;
  const factor=hcsUnitMil?KM_PER_MILE:1/KM_PER_MILE,unit=hcsUnitMil?'mil':'km';
  const xSlider=document.getElementById('hx'),bSlider=document.getElementById('hb');
  xSlider.min=String(Number(xSlider.min)/factor);xSlider.max=String(Number(xSlider.max)/factor);
  bSlider.min=String(Number(bSlider.min)*factor);bSlider.max=String(Number(bSlider.max)*factor);
  xSlider.step='any';bSlider.step='any';
  xSlider.value=String(x/factor);bSlider.value=String(b*factor);
  sv('hcs-unit-btn',hcsUnitMil?'MIL':'KM');sv('hcs-unit-label',hcsUnitMil?'Mil (mil)':'Kilometer (km)');
  sv('hx-label','x — Jarak haul pulang-pergi ('+unit+')');sv('hb-label','b — Konstanta per '+unit+' (jam/'+unit+')');
  sv('hx-min',fmt(Number(xSlider.min))+unit);sv('hx-max',fmt(Number(xSlider.max))+unit);
  sv('hb-unit-start',fmt(Number(bSlider.min),6));sv('hb-unit-end',fmt(Number(bSlider.max),6));
  uHCS();
}

const hcsFields={pc:'hp',uc:'hu',dbc:'hd',s:'hs',x:'hx',a:'ha',b:'hb',H:'hH',w:'hw',t1:'ht1',t2:'ht2',vd:'hvd',c:'hc',f:'hf'};

const hcsOutputs=['rHp','rHh','rHt','rHnd1','rHnd2','rHdone','rHleft','rHfleet','sHp','sHh','sHt','sHnd1','sHnd2'];

function uHCS(){
  try{
    const p=readFields(hcsFields),v=calcHCS(p),unit=hcsUnitMil?'mil':'km';
    for(const [key,id] of Object.entries(hcsFields)){
      const suffix=['pc','uc','dbc','s','a','t1','t2'].includes(key)?'h':key==='x'?unit:key==='b'?'h/'+unit:key==='H'?'jam':['vd','c'].includes(key)?'m³':'';
      sv(id+'v',key==='w'?fmt(p.w*100,0)+'%':fmt(p[key],key==='b'?6:3)+suffix);
    }
    sv('rHp',v.P.toFixed(4));sv('rHh',v.h.toFixed(4));sv('rHt',v.T.toFixed(4));
    sv('rHnd1',v.capacityRaw.toFixed(2)+' → '+v.capacity);sv('rHnd2',v.required);
    sv('rHdone',v.done);sv('rHleft',fmt(v.remaining));sv('rHfleet',v.fleet===null?'Tidak layak':v.fleet);
    sv('hcs-status',planMessage(v));
    sv('sHp',`${fmt(p.pc)} + ${fmt(p.uc)} + ${fmt(p.dbc)} = ${v.P.toFixed(4)} jam`);
    sv('sHh',`${fmt(p.a)} + ${fmt(p.b,6)}×${fmt(p.x,6)} = ${v.h.toFixed(4)} jam`);
    sv('sHt',`${v.P.toFixed(4)} + ${fmt(p.s)} + ${v.h.toFixed(4)} = ${v.T.toFixed(4)} jam`);
    sv('sHnd1',`max(0, floor([${p.H}×(1−${p.w}) − (${fmt(p.t1,6)}+${fmt(p.t2,6)})] / ${v.T.toFixed(4)})) = ${v.capacity} rit`);
    sv('sHnd2',`ceil(${p.vd} / (${p.c}×${p.f})) = ${v.required} rit`);
    const maxX=Number(document.getElementById('hx').max),points=Array.from({length:41},(_,i)=>({x:i*maxX/40,y:v.P+p.s+p.a+p.b*i*maxX/40}));
    hC=replaceChart(hC,'hcs-ch',{type:'line',data:{datasets:[{label:'T_HCS',data:points,borderColor:'#2e7d46',backgroundColor:'rgba(46,125,70,.12)',borderWidth:2,pointRadius:0,fill:true},{label:'x='+fmt(p.x)+unit,data:[{x:p.x,y:v.T}],backgroundColor:'#2e7d46',pointRadius:7,type:'scatter'}]},options:co('Jarak ('+unit+')','Waktu (jam)',true)});
  }catch(error){clearOutputs(hcsOutputs);sv('hcs-status',error.message);hC=clearChart(hC);}
}

function loadSoalLatihan(){
  if(!hcsUnitMil)toggleHCSUnit();
  const values={hp:.4,hu:0,hd:.1,hs:.133,hx:31,ha:.016,hb:.018,hH:8,hw:.15,ht1:15/60,ht2:20/60};
  Object.entries(values).forEach(([id,value])=>document.getElementById(id).value=String(value));
  sw('hcs',document.querySelectorAll('.tb')[1]);window.scrollTo({top:0,behavior:'smooth'});
}

uHCS();uSCS();uCmp();doReg();kHCS();renderSCSCase();
if(typeof Chart!=='function'){
  sv('chart-status','Grafik belum tersedia karena pustaka grafik belum termuat. Perhitungan angka tetap dapat digunakan.');
  document.getElementById('chart-status').hidden=false;
}
