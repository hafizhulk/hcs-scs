let cBar=null,cLine=null;
const comparisonFields={x:'cx',a:'ca',b:'cb',ph:'cph',sh:'csh',ps:'cps',ss:'css2',qh:'cq-hcs',qs:'cq-scs',vd:'cvd',H:'cH',w:'cw',t1:'ct1',t2:'ct2'};

function uCmp(){
  try{
    const p=readFields(comparisonFields),v=calcComparison(p);
    for(const id of ['cx','ca','cb'])sv(id+'v',fmt(gv(id))+(id==='cx'?'km':''));
    for(const system of ['hcs','scs']){
      const plan=v[system];sv('cr-'+system,plan.T.toFixed(3));sv('cr-'+system+'-unit',plan.perVolume.toFixed(4));
      sv('cr-'+system+'-nd',plan.required);sv('cr-'+system+'-H',plan.Hneeded.toFixed(2));sv('cr-'+system+'-fleet',plan.fleet===null?'Tidak layak':plan.fleet);
    }
    sv('compare-status',`Beban bersama ${p.vd} m³ lepas/hari; jam kerja ${p.H} jam dan off-route ${fmt(p.w*100,0)}%. Muatan efektif: HCS ${p.qh} m³/rit, SCS ${p.qs} m³/rit.`);
    const mx=Math.max(v.hcs.T,v.scs.T);
    function mb(label,time,color){return `<div class="sl"><span>${label}</span><span>${fmt(time)}h</span></div><div class="mbar"><div class="mf ${color}" style="width:${time/mx*100}%"></div></div>`;}
    document.getElementById('cmp-bd').innerHTML=`<div class="tgrid"><div>HCS — ${fmt(v.hcs.T)} jam/rit${mb('Pickup',p.ph,'fg')}${mb('At-site',p.sh,'fg')}${mb('Haul',v.h,'fg')}</div><div>SCS — ${fmt(v.scs.T)} jam/rit${mb('Pickup',p.ps,'fo2')}${mb('At-site',p.ss,'fo2')}${mb('Haul',v.h,'fo2')}</div></div>`;
    cBar=replaceChart(cBar,'cmp-bar',{type:'bar',data:{labels:['Pickup','At-site','Haul','TOTAL'],datasets:[{label:'HCS',data:[p.ph,p.sh,v.h,v.hcs.T],backgroundColor:'rgba(46,125,70,.7)'},{label:'SCS',data:[p.ps,p.ss,v.h,v.scs.T],backgroundColor:'rgba(192,90,46,.7)'}]},options:co('Komponen','Waktu (jam/rit)')});
    const points=(P,s,q)=>Array.from({length:81},(_,x)=>({x,y:(P+s+p.a+p.b*x)/q}));
    cLine=replaceChart(cLine,'cmp-line',{type:'line',data:{datasets:[{label:'HCS',data:points(p.ph,p.sh,p.qh),borderColor:'#2e7d46',pointRadius:0},{label:'SCS',data:points(p.ps,p.ss,p.qs),borderColor:'#c05a2e',pointRadius:0}]},options:co('Jarak pulang-pergi (km)','Waktu (jam/m³)',true)});
    const verdict=v.winner===null?'Waktu operasi per m³ setara.':`${v.winner} membutuhkan waktu operasi lebih rendah per m³.`;
    sv('cmp-verdict',`${verdict} Selisih ${v.diff.toFixed(4)} jam/m³ pada x=${p.x} km.\nUntuk seluruh beban: HCS ${v.hcs.Hneeded.toFixed(2)} jam, SCS ${v.scs.Hneeded.toFixed(2)} jam (estimasi satu kendaraan dengan ritasi penuh). Bandingkan pula kebutuhan armada di atas.`);
    document.getElementById('cmp-verdict').style.whiteSpace='pre-line';
  }catch(error){
    for(const system of ['hcs','scs'])clearOutputs(['','-unit','-nd','-H','-fleet'].map(suffix=>'cr-'+system+suffix));
    sv('compare-status',error.message);sv('cmp-bd','');sv('cmp-verdict','');cBar=clearChart(cBar);cLine=clearChart(cLine);
  }
}
