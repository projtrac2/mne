 <tr id="rowlines">
 	<td>
 		<textarea name="observations" id="observations" cols="60" rows="8" style="font-size:13px; color:#000; width:99%" required="required"></textarea>
 		<script>
 			CKEDITOR.replace('observations', {
 				height: '150px',
 				toolbar: [{
 						name: 'clipboard',
 						items: ['Cut', 'Copy', 'Paste', 'PasteText', 'PasteFromWord', '-', 'Undo', 'Redo']
 					},
 					{
 						name: 'editing',
 						items: ['Find', 'Replace', '-', 'SelectAll', '-', 'Scayt']
 					},
 					{
 						name: 'insert',
 						items: ['Image', 'Flash', 'Table', 'HorizontalRule', 'Smiley', 'SpecialChar', 'PageBreak', 'Iframe']
 					},
 					'/',
 					{
 						name: 'styles',
 						items: ['Styles', 'Format']
 					},
 					{
 						name: 'basicstyles',
 						items: ['Bold', 'Italic', 'Strike', '-', 'RemoveFormat']
 					},
 					{
 						name: 'paragraph',
 						items: ['NumberedList', 'BulletedList', '-', 'Outdent', 'Indent', '-', 'Blockquote']
 					},
 					{
 						name: 'links',
 						items: ['Link', 'Unlink', 'Anchor']
 					},
 					{
 						name: 'tools',
 						items: ['Maximize', '-', 'About']
 					}
 				]

 			});
 		</script>
 	</td>
 </tr>