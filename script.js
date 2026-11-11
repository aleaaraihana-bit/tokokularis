document.addEventListener('DOMContentLoaded',()=>{
 document.querySelectorAll('[data-confirm]').forEach(el=>el.addEventListener('click',e=>{if(!confirm(el.dataset.confirm||'Yakin ingin melanjutkan?'))e.preventDefault()}));
 document.querySelectorAll('[data-preview]').forEach(input=>input.addEventListener('change',()=>{const img=document.querySelector(input.dataset.preview);if(img&&input.files[0])img.src=URL.createObjectURL(input.files[0]);}));
 document.querySelectorAll('[data-search-table]').forEach(input=>input.addEventListener('input',()=>{const table=document.querySelector(input.dataset.searchTable);if(!table)return;const q=input.value.toLowerCase();table.querySelectorAll('tbody tr').forEach(tr=>tr.hidden=!tr.innerText.toLowerCase().includes(q));}));
});
