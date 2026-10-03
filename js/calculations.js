// All volumes are loose (before compaction), unless stated otherwise.
// Count whole containers/trips without letting floating-point noise add a trip.
function nearInteger(value){
  const rounded=Math.round(value);
  return (rounded!==0||value===0)&&Math.abs(value-rounded)<=16*Number.EPSILON*Math.max(1,Math.abs(value))?rounded:value;
}

function wholeCount(value,round){
  const result=round(nearInteger(value));
  if(!Number.isSafeInteger(result)||result<0)throw new Error('Jumlah kontainer atau ritasi di luar rentang perhitungan.');
  return result;
}

function ceilCount(value){return wholeCount(value,Math.ceil);}

function floorCount(value){return wholeCount(value,Math.floor);}

function finiteInputs(p){
  for(const [key,value] of Object.entries(p))if(!Number.isFinite(value))throw new Error('Isi '+key+' dengan angka yang valid.');
}

function nonnegative(p,keys){
  for(const key of keys)if(p[key]<0)throw new Error(key+' tidak boleh negatif.');
}

function validateDay(p){
  finiteInputs(p);
  nonnegative(p,['vd','t1','t2']);
  if(p.H<=0)throw new Error('Jam kerja H harus lebih besar dari nol.');
  if(p.w<0||p.w>=1)throw new Error('Off-route w harus 0 ≤ w < 1.');
}

function dailyPlan(T,q,p){
  validateDay(p);
  if(!Number.isFinite(T)||T<=0||!Number.isFinite(q)||q<=0)throw new Error('Waktu dan muatan efektif per ritasi harus positif.');
  const available=Math.max(0,p.H*(1-p.w)-p.t1-p.t2);
  const capacityRaw=available/T,capacity=floorCount(capacityRaw),required=ceilCount(p.vd/q);
  const done=Math.min(capacity,required),remaining=Math.max(0,p.vd-done*q);
  const fleet=required===0?0:capacity===0?null:ceilCount(required/capacity);
  const Hneeded=required===0?0:(p.t1+p.t2+required*T)/(1-p.w);
  if(!Number.isFinite(Hneeded))throw new Error('Waktu harian di luar rentang perhitungan.');
  return {available,capacityRaw,capacity,required,done,remaining,fleet,Hneeded,q,T};
}

function calcHCS(p){
  finiteInputs(p);
  nonnegative(p,['pc','uc','dbc','s','x','a','b']);
  if(p.c<=0||p.f<=0||p.f>1)throw new Error('Volume kontainer c harus positif dan keterisian f harus 0 < f ≤ 1.');
  const P=p.pc+p.uc+p.dbc,h=p.a+p.b*p.x,T=P+p.s+h;
  return {P,h,T,...dailyPlan(T,p.c*p.f,{H:p.H,w:p.w,t1:p.t1,t2:p.t2,vd:p.vd})};
}

function calcSCS(p){
  finiteInputs(p);
  nonnegative(p,['uc','dbc','s','x','a','b','vd','t1','t2']);
  if(p.V<=0||p.c<=0)throw new Error('Volume truk V dan kontainer c harus positif.');
  if(p.r<1)throw new Error('Rasio kompaksi r harus ≥ 1.');
  if(p.f<=0||p.f>1)throw new Error('Keterisian f harus 0 < f ≤ 1.');
  if(p.w<0||p.w>=1)throw new Error('Off-route w harus 0 ≤ w < 1.');
  if(!Number.isSafeInteger(p.np)||p.np<1)throw new Error('Jumlah lokasi n_p harus bilangan bulat positif.');
  const containerVolume=p.c*p.f,CTraw=p.V*p.r/containerVolume,CT=floorCount(CTraw);
  if(CT<1)throw new Error('Kapasitas truk tidak cukup untuk satu kontainer utuh.');
  if(p.np>CT)throw new Error('Jumlah lokasi n_p tidak boleh melebihi kapasitas '+CT+' kontainer per ritasi.');
  const containers=ceilCount(p.vd/containerVolume),lastContainers=containers%CT,fullTrips=(containers-lastContainers)/CT;
  const Nd=fullTrips+(lastContainers>0?1:0);
  // For the partial trip, estimate locations using the full-route average.
  const lastLocations=lastContainers===0?0:Math.min(lastContainers,ceilCount(lastContainers*p.np/CT));
  const P=CT*p.uc+(p.np-1)*p.dbc,h=p.a+p.b*p.x,T=P+p.s+h;
  const lastP=lastContainers*p.uc+Math.max(0,lastLocations-1)*p.dbc;
  const pickupTotal=fullTrips*P+lastP;
  const H=Nd===0?0:(p.t1+p.t2+pickupTotal+Nd*(p.s+h))/(1-p.w);
  const q=CT*containerVolume;
  if(![P,h,T,lastP,pickupTotal,H,q].every(Number.isFinite)||T<=0||q<=0)throw new Error('Waktu atau muatan hasil perhitungan tidak valid.');
  return {CTraw,CT,containers,fullTrips,lastContainers,lastLocations,Nd,P,h,T,lastP,pickupTotal,H,q};
}

function fitHaul(pairs){
  if(pairs.length<3)throw new Error('Diperlukan minimal 3 pasangan lengkap dengan variasi jarak.');
  for(const {x,h} of pairs){
    if(!Number.isFinite(x)||!Number.isFinite(h)||x<0||h<0)throw new Error('Jarak dan waktu survei harus angka hingga yang tidak negatif.');
  }
  const n=pairs.length,mX=pairs.reduce((sum,p)=>sum+p.x/n,0),mH=pairs.reduce((sum,p)=>sum+p.h/n,0);
  const sxx=pairs.reduce((sum,p)=>sum+(p.x-mX)**2,0),sxy=pairs.reduce((sum,p)=>sum+(p.x-mX)*(p.h-mH),0);
  if(!Number.isFinite(sxx)||!Number.isFinite(sxy)||sxx===0)throw new Error('Jarak survei harus bervariasi; semua nilai x sama atau di luar rentang.');
  const constantH=pairs.every(p=>p.h===pairs[0].h);
  const b=constantH?0:sxy/sxx,a=constantH?pairs[0].h:mH-b*mX;
  const ssTot=pairs.reduce((sum,p)=>sum+(p.h-mH)**2,0),ssRes=pairs.reduce((sum,p)=>sum+(p.h-(a+b*p.x))**2,0);
  if(![a,b,ssTot,ssRes].every(Number.isFinite))throw new Error('Koefisien regresi di luar rentang perhitungan.');
  const r2=constantH?null:Math.max(0,Math.min(1,1-ssRes/ssTot));
  const minX=Math.min(...pairs.map(p=>p.x)),maxX=Math.max(...pairs.map(p=>p.x));
  const warnings=[];
  if(b<=0)warnings.push('Slope b tidak positif; model haul perlu diperiksa secara fisik.');
  if(a<0)warnings.push('Intercept a negatif; periksa batas data dan jangan mengekstrapolasi.');
  if(r2===null)warnings.push('Waktu h konstan; R² tidak terdefinisi.');
  return {a,b,r2,n,minX,maxX,warnings};
}

function calcComparison(p){
  finiteInputs(p);
  nonnegative(p,['x','a','b','ph','sh','ps','ss']);
  const h=p.a+p.b*p.x,day={H:p.H,w:p.w,t1:p.t1,t2:p.t2,vd:p.vd};
  const hcs=dailyPlan(p.ph+p.sh+h,p.qh,day),scs=dailyPlan(p.ps+p.ss+h,p.qs,day);
  hcs.perVolume=hcs.T/hcs.q;scs.perVolume=scs.T/scs.q;
  const diff=Math.abs(hcs.perVolume-scs.perVolume);
  const equal=diff<=32*Number.EPSILON*Math.max(1,hcs.perVolume,scs.perVolume);
  return {h,hcs,scs,winner:equal?null:hcs.perVolume<scs.perVolume?'HCS':'SCS',diff};
}

if(typeof module!=='undefined'&&module.exports){module.exports={nearInteger,wholeCount,ceilCount,floorCount,finiteInputs,nonnegative,validateDay,dailyPlan,calcHCS,calcSCS,fitHaul,calcComparison};}
