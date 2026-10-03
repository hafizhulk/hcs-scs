function sv(id,value){const e=document.getElementById(id);if(e)e.textContent=value;}

function gv(id){const value=document.getElementById(id).value.trim();return value===''?NaN:Number(value);}

function readFields(mapping){return Object.fromEntries(Object.entries(mapping).map(([key,id])=>[key,gv(id)]));}

function fmt(value,digits=3){return Number(value.toFixed(digits)).toString();}

function clearOutputs(ids){ids.forEach(id=>sv(id,'—'));}

function planMessage(result){
  if(result.required===0)return 'Tidak ada sampah yang perlu diangkut. Kebutuhan armada: 0 kendaraan.';
  if(result.capacity===0)return 'Tidak ada ritasi utuh yang muat dalam jam kerja. Seluruh '+fmt(result.remaining)+' m³ belum terangkut; tambah jam kerja atau ubah rute.';
  if(result.required<=result.capacity)return 'Seluruh sampah dapat diangkut satu kendaraan dalam '+result.required+' ritasi. Waktu yang diperlukan: '+fmt(result.Hneeded,2)+' jam.';
  return 'Satu kendaraan belum mencukupi: kebutuhan '+result.required+' ritasi, kapasitas '+result.capacity+' ritasi.\nBelum terangkut: '+fmt(result.remaining)+' m³/hari. Armada minimum: '+result.fleet+' kendaraan identik.';
}

function sw(tab,el){
  document.querySelectorAll('.sec').forEach(s=>s.classList.remove('active'));
  document.querySelectorAll('.tb').forEach(b=>b.classList.remove('active'));
  document.getElementById('tab-'+tab).classList.add('active');el.classList.add('active');
  ({hcs:uHCS,scs:uSCS,compare:uCmp,calib:doReg,cases:kHCS}[tab]||function(){})();
  if(typeof syncRangeNumbers==='function'){try{syncRangeNumbers();}catch(e){}}
}
