//DOM��S�ēǂݍ���ł��珈������
$(function(){
	//[.syncer-acdn]�ɃN���b�N�C�x���g��ݒ肷��
	$( ".sub-header" ).click( function(){
		//[data-target]�̑����l��������
		var target = $(this).data( "target" ) ;

		//[target]�Ɠ������O��ID�����v�f��[slideToggle()]�����s����
		$( "#" + target ).slideToggle() ;

		//�I��
		return false ;

	} ) ;

}) ;
