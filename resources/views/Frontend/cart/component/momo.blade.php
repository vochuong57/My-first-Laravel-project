@if ($momo['m2signature'] == $momo['partnerSignature'])
    @if ($momo['resultCode'] == '0') 
        <div class="alert alert-success"><strong>Tình trạng thanh toán: </strong>{{ $momo['message'] }}</div>
    @else
        <div class="alert alert-danger"><strong>Tình trạng thanh toán: </strong>{{ $momo['message'] }} / {{ $momo['localMessage']}}</div>
    @endif
    
@else 
    <div class="alert alert-danger">Giao dịch của bạn có thể đã bị hack, bạn vui lòng kiểm tra lại chữ ký của bạn và trả lại chữ ký</div>
@endif
