# What is this ?
source code for c.2ch.sc
# summary
## �d�g�݁E��{�I�ȍl����
��ɁC���C�u�������͂��ׂĕʃt�@�C���ɂ��ă��C������Ăяo��.

�ύX�E�C�����K�v�ȏꍇ,�Y�����郉�C�u�����t�@�C���̏C��������ڎw��.

�ʃt�@�C���ɂ��邱�Ƃɂ��,�����e�i���X����ǐ���R�[�h�̍ė��p�������܂�͂�.

��{�I��,���r���[�ȃI�u�W�F�N�g�w���[�����l���č���Ă��܂�.

## ���C�Z���X
MIT License�Ƃ��܂�.

���̈�,�K�v�ƂȂ�ʃ��C�u�������͓����������܂���.

�g�p�҂̐ӔC�ɂ�����,�擾���w��̃f�B���N�g���ɐݒu���Ă�������.

## �f�B���N�g���\������ѐ���
cache : �L���b�V���p�t�H���_

configs : config�p�t�H���_(����)

libs : �Ǝ��̃��C�u�����p�t�H���_

libs/bbslist.class.php : bbsmenu ���p�[�X���郉�C�u����

libs/common.class.php : ���ʂ̃��C�u����

libs/get.class.php : dat��subject ���擾���郉�C�u����

libs/parer.class.php : dat��subject.txt ���p�[�X���郉�C�u����

libs/util.class.php : ���[�e�B���e�B�̃��C�u����(���g�p)

imports : �O��(�T�[�h�p�[�e�B)����C���|�[�g�������C�u�����p�t�H���_

imports/cache : cache lite (PEAR CacheLite) *1

ime.nu.php : �����N�N�b�V����

Smarty : html�o�͂���t���[�����[�N�p�t�H���_(Smarty) *2

plugins : Smarty�̃v���O�C���p�t�H���_ *3

templates : �e��ʂ̃p�^�[��(�e���v���[�g)�p�t�H���_

config.cgi : �e��ݒ�p�t�@�C��

.htaccess : 

index.php : ���C���̃v���O����

## �K�v�ȃ��C�u����
### Cache_Lite 
https://pear.php.net/package/Cache_Lite/ ���擾��,*1�̃t�H���_�ɕۑ�
### Smarty
http://www.smarty.net/ ���擾��,�{�̂�*2�̃t�H���_��,�v���O�C����*3�̃t�H���_�ɕۑ�
## �ύX���K�v�ȃt�@�C��
config.cgi : /path/to/ ��ݒu�f�B���N�g���ɓK��������

libs/bbslist.class.php : $json_local��/path/to/��ݒu�f�B���N�g���ɓK��������