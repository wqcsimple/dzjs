<?php
use fayfox\helpers\Html;
use fayfox\models\File;
use fayfox\helpers\Date;
use fayfox\core\Sql;
use fayfox\models\tables\Messages;

$children = array();
if (!$data['is_terminal']) {
    $sql = new Sql();
    $children = $sql->from('messages', 'm')
        ->joinLeft('users', 'u', 'm.user_id = u.id', 'nickname,avatar,nickname')
        ->joinLeft('messages', 'm2', 'm.parent = m2.id', 'user_id AS parent_user_id')
        ->joinLeft('users', 'u2', 'm2.user_id = u2.id', 'nickname AS parent_nickname')
        ->where(array(
            'm.root = ' . $data['id'],
            'm.status = ' . Messages::STATUS_APPROVED,
            'm.deleted = 0',
        ))
        ->order('id')
        ->fetchAll();
} ?>
<li id="msg-<?php echo $data['id'] ?>">
    <div class="avatar">
        <?php echo Html::img($data['avatar'], File::PIC_THUMBNAIL, array(
            'alt' => $data['nickname'],
            'spare' => 'avatar',
        )) ?>
    </div>
    <div class="meta">
        <?php echo Html::link($data['nickname'], '', array(
            'class' => 'user-link',
        )) ?>
    </div>
    <div class="message-content"><?php echo nl2br(Html::encode($data['content'])) ?></div>
    <ul class="children-list">
        <?php foreach ($children as $m) { ?>
            <li>
			<span class="un"><?php
                if ($m['user_id'] == $m['parent_user_id']) {
                    echo Html::link($m['nickname'], array(
                        'u/' . $m['user_id'],
                    )), ' : ';
                } else {
                    echo Html::link($m['nickname'], array(
                        'u/' . $m['user_id'],
                    )),
                    ' 回复 ',
                    Html::link($m['parent_nickname'], array(
                        'u/' . $m['parent_user_id'],
                    )),
                    ' : ';
                }
                ?></span>
                <p><?php echo nl2br(Html::encode($m['content'])) ?></p>
                <?php echo Html::link('', 'javascript:;', array(
                    'title' => '回复',
                    'class' => 'icon-comment reply-child-link',
                    'data-parent' => $m['id'],
                )) ?>
            </li>
        <?php } ?>
    </ul>

</li>