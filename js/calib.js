let calC=null;
function addCalRow(){
  const body=document.getElementById('cal-body'),tr=document.createElement('tr'),index=body.querySelectorAll('tr').length+1;
  tr.innerHTML=`<td>${index}</td><td><input type="number" class="cx2" min="0" step="any" oninput="doReg()"></td><td><input type="number" class="ch2" min="0" step="any" oninput="doReg()"></td>`;
  body.appendChild(tr);doReg();
}

function doReg(){
  try{
    let incomplete=0;const pairs=[];
    for(const row of document.querySelectorAll('#cal-body tr')){
      const rawX=row.querySelector('.cx2').value.trim(),rawH=row.querySelector('.ch2').value.trim();
      if(rawX===''||rawH===''){if(rawX!==''||rawH!=='')incomplete++;continue;}
      pairs.push({x:Number(rawX),h:Number(rawH)});
    }
    const fit=fitHaul(pairs);
    sv('ra',fit.a.toFixed(4)+' jam');sv('rb',fit.b.toFixed(4)+' jam/km');sv('rr2',fit.r2===null?'Tidak terdefinisi':fit.r2.toFixed(4));
    document.getElementById('rr2').style.color=fit.warnings.length?'#c05a2e':fit.r2>.9?'#2e7d46':fit.r2>.7?'#97700f':'#c05a2e';
    const speed=fit.b>0&&Number.isFinite(1/fit.b)?fmt(1/fit.b,1)+' km/jam':'Tidak dapat ditafsirkan';
    sv('rinterp',`Model: h = ${fit.a.toFixed(4)} + ${fit.b.toFixed(4)}·x\n${fit.n} pasangan lengkap; ${incomplete} baris belum lengkap diabaikan.\nRentang survei: ${fmt(fit.minX)}–${fmt(fit.maxX)} km pulang-pergi.\nTambahan waktu: ${fmt(fit.b*60,3)} menit/km. Kecepatan berdasarkan slope: ${speed}.\n${fit.warnings.join('\n')}\n${fit.r2!==null?(fit.r2>.95?'Kecocokan data tinggi.':fit.r2>.8?'Kecocokan data cukup; tambah survei.':'Kecocokan data rendah; periksa model.'):'Variasi waktu tidak cukup untuk menilai kecocokan.'} R² saja tidak membuktikan kelayakan desain; periksa kondisi rute, residual, dan data validasi.`);
    document.getElementById('rinterp').style.whiteSpace='pre-line';
    calC=replaceChart(calC,'cal-ch',{type:'scatter',data:{datasets:[{label:'Data survei',data:pairs.map(p=>({x:p.x,y:p.h})),backgroundColor:'#2e7d46',pointRadius:6},{label:'Regresi dalam rentang survei',type:'line',data:[{x:fit.minX,y:fit.a+fit.b*fit.minX},{x:fit.maxX,y:fit.a+fit.b*fit.maxX}],borderColor:'#c05a2e',borderWidth:2,pointRadius:0}]},options:co('Jarak pulang-pergi (km)','Waktu haul (jam)',true)});
  }catch(error){clearOutputs(['ra','rb','rr2']);sv('rinterp',error.message);calC=clearChart(calC);}
}
