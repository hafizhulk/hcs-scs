let sC=null;
const scsFields={V:'sv',r:'sr',c:'sc',f:'sf',uc:'suc',np:'snp',dbc:'sdbc',s:'ss',x:'sx',a:'sa',b:'sb',vd:'svd',w:'sw2',t1:'st1',t2:'st2'};

const scsOutputs=['rSct','rSp','rSt','rSnd','rSH','sSct','sSp','sSh','sSt','sSnd','sSH'];

function uSCS(){
  try{
    const p=readFields(scsFields),v=calcSCS(p);
    for(const [key,id] of Object.entries(scsFields)){
      const suffix=['V','c','vd'].includes(key)?'m³':key==='r'?'×':key==='np'?' lok':key==='x'?'km':key==='b'?'h/km':['uc','dbc','s','a','t1','t2'].includes(key)?'h':'';
      sv(id+'v',key==='w'?fmt(p.w*100,0)+'%':fmt(p[key])+suffix);
    }
    sv('rSct',v.CT);sv('rSp',v.P.toFixed(3));sv('rSt',v.T.toFixed(3));sv('rSnd',v.Nd);sv('rSH',v.H.toFixed(2));
    const last=v.lastContainers>0?`Ritasi terakhir: ${v.lastContainers} kontainer di sekitar ${v.lastLocations} lokasi; pickup ${fmt(v.lastP)} jam.`:'Semua ritasi penuh.';
    sv('scs-status',v.Nd===0?'Tidak ada sampah yang perlu diangkut; waktu kerja pengangkutan 0 jam.':`Muatan efektif: ${fmt(v.q)} m³ lepas/rit; kebutuhan ${v.containers} kontainer/hari.\n${v.fullTrips} ritasi penuh${v.lastContainers>0?' + 1 ritasi sebagian':''}. ${last}\n${v.H>8?'Waktu melebihi 8 jam satu kendaraan; evaluasi pembagian rute dan armada.':'Waktu pengangkutan berada dalam 8 jam.'}`);
    sv('sSct',`floor((${p.V}×${p.r})/(${p.c}×${p.f})) = floor(${fmt(v.CTraw)}) = ${v.CT}`);
    sv('sSp',`${v.CT}×${fmt(p.uc)} + (${p.np}−1)×${fmt(p.dbc)} = ${fmt(v.P)} jam`);
    sv('sSh',`${fmt(p.a)} + ${fmt(p.b)}×${p.x} = ${fmt(v.h)} jam`);
    sv('sSt',`${fmt(v.P)} + ${fmt(p.s)} + ${fmt(v.h)} = ${fmt(v.T)} jam`);
    sv('sSnd',`C_hari = ceil(${p.vd}/(${p.c}×${p.f})) = ${v.containers}; Nd = ceil(${v.containers}/${v.CT}) = ${v.Nd}`);
    sv('sSH',v.Nd===0?'0 jam':`[(${p.t1}+${p.t2}) + ${fmt(v.pickupTotal)} + ${v.Nd}×(${fmt(p.s)}+${fmt(v.h)})]/(1−${p.w}) = ${v.H.toFixed(2)} jam`);
    const points=Array.from({length:41},(_,i)=>{const ct=1+(v.CT-1)*i/40,np=Math.min(p.np,Math.ceil(ct*p.np/v.CT));return {x:ct,y:ct*p.uc+(np-1)*p.dbc};});
    sC=replaceChart(sC,'scs-ch',{type:'line',data:{datasets:[{label:'P_SCS — sebaran lokasi merata',data:points,borderColor:'#c05a2e',borderWidth:2,pointRadius:0},{label:'CT='+v.CT,data:[{x:v.CT,y:v.P}],backgroundColor:'#c05a2e',pointRadius:7,type:'scatter'}]},options:co('Kontainer/rit','Pickup (jam)',true)});
  }catch(error){clearOutputs(scsOutputs);sv('scs-status',error.message);sC=clearChart(sC);}
}
