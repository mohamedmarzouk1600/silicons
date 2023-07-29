

{{ $title }}

<img id='barcode' 
src="https://api.qrserver.com/v1/create-qr-code/?data={{ $qr }}&amp;size=100x100" 
alt="{{ $data->{$row->field} }}" 
title="{{ $data->{$row->field} }}"
width="50" 
height="50" />

{{ $content }}

