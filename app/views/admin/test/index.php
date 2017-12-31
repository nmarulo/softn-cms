<?php

use SoftnCMS\util\HTML;
use SoftnCMS\controllers\ViewController;

var_dump(ViewController::getViewData('button'));
var_dump($_POST);
echo "Page Testing <br/>";
echo ViewController::getViewData('button');
echo ViewController::getViewData('keyTest');
//text, password, datetime, datetime-local, date, month, time, week, number, email, url, search, tel, color
?>

<div class="page-container">

    <form class="form-horizontal" method="post">
        <p>input hidden</p>
        <?php HTML::inputHidden('inputHiddenTesting', 'inputHiddenValue'); ?>
        <div class="form-group">
            <?php
            HTML::inputLabel([
                'labelClass' => 'control-label',
                'labelText'  => 'testingLabel',
                'id'         => 'test-id',
                'class'      => 'form-control',
                'name'       => 'inputTextTesting',
                'value'      => 'inputValueTesting',
                'data'       => [
                    'placeholder' => 'placeholder test',
                    'autofocus'   => '',
                ],
            ]);
            ?>
        </div>
        <div class="form-group">
            <?php HTML::inputPassword('inputPasswordTesting', 'labelText - InputPassword - HTML::class'); ?>
        </div>
        <div class="form-group">
            <label class="control-label" for="inputId">labelText - inputDatetime-local</label>
            <input id="inputId" class="form-control" type="datetime-local" name="inputName" value="inputValue">
        </div>
        <div class="form-group">
            <label class="control-label" for="inputId">labelText - inputDate</label>
            <input id="inputId" class="form-control" type="date" name="inputName" value="inputValue">
        </div>
        <div class="form-group">
            <label class="control-label" for="inputId">labelText - inputMonth</label>
            <input id="inputId" class="form-control" type="month" name="inputName" value="inputValue">
        </div>
        <div class="form-group">
            <label class="control-label" for="inputId">labelText - inputTime</label>
            <input id="inputId" class="form-control" type="time" name="inputName" value="inputValue">
        </div>
        <div class="form-group">
            <label class="control-label" for="inputId">labelText - inputWeek</label>
            <input id="inputId" class="form-control" type="week" name="inputName" value="inputValue">
        </div>
        <div class="form-group">
            <?php HTML::inputNumber('inputNumberTesting', 'labelText - inputNumber - HTML::class', '12345'); ?>
        </div>
        <div class="form-group">
            <?php HTML::inputEmail('inputEmailTesting', 'labelText - inputEmail - HTML::class', 'info@softn.red'); ?>
        </div>
        <div class="form-group">
            <?php HTML::inputUrl('inputUrlTesting', 'labelText - inputUrl - HTML::class', 'http://softn.red'); ?>
        </div>
        <div class="form-group">
            <label class="control-label" for="inputId">labelText - inputSearch</label>
            <input id="inputId" class="form-control" type="search" name="inputName" value="inputValue">
        </div>
        <div class="form-group">
            <?php
            HTML::inputTel('inputTelTesting', 'labelText - inputTel - HTML::class');
            ?>
        </div>
        <div class="form-group">
            <label class="control-label" for="inputId">labelText</label>
            <input id="inputId" class="form-control" type="color" name="inputName" value="inputValue">
        </div>
        <div class="form-group">
            <?php
            $optionsDataList           = [
                [
                    'value' => '123',
                    'text'  => 'value 123',
                ],
                [
                    'value' => '456',
                    'text'  => 'value 456',
                ],
                [
                    'value' => '789',
                    'text'  => 'value 789',
                ],
                [
                    'value' => '000',
                    'text'  => 'value 000',
                ],
            ];
            $closureGetValueAndGetText = function($option) {
                return [
                    'optionValue' => $option['value'],
                    'optionText'  => $option['text'],
                ];
            };

            $options = HTML::createSelectOption($optionsDataList, $closureGetValueAndGetText, 456);
            $data    = [
                'labelText'  => 'labelText - selectOne - HTML::class',
                'labelClass' => 'control-label',
                'class'      => 'form-control',
            ];
            HTML::selectOne('selectOneTesting', $options, $data);

            $data['labelText'] = 'labelText - selectMultiple - HTML::class';
            $options           = HTML::createSelectOption($optionsDataList, $closureGetValueAndGetText, [
                456,
                000,
            ]);
            HTML::selectMultiple('selectMultipleTesting', $options, $data);
            ?>
        </div>
        <div class="form-group">
            <?php HTML::textAreaBasic('textAreaTesting', 'conentTextArea HTML::class', [
                'labelClass' => 'control-label',
                'labelText'  => 'textArea - HTML::class',
                'class'      => 'form-control',
            ]); ?>
        </div>
        <div class="form-group">
            <div class="checkbox">
                <?php
                HTML::inputCheckbox('inputCheckBoxName01', 'checkBox HTML::class - text');
                ?>
            </div>
            <div class="checkbox">
                <?php
                HTML::inputCheckbox('inputCheckBoxName02', 'checkBox HTML::class - text');
                ?>
            </div>
        </div>
        <div class="form-group">
            <?php
            HTML::inputCheckbox('inputCheckBoxName03', 'checkBox HTML::class - text', 'textvalue', ['labelClass' => 'checkbox-inline']);
            ?>
            <?php
            HTML::inputCheckbox('inputCheckBoxName04', 'checkBox HTML::class - text', 'textvalue', ['labelClass' => 'checkbox-inline']);
            ?>
            <?php
            HTML::inputCheckbox('inputCheckBoxName05', 'checkBox HTML::class - text', 'textvalue', ['labelClass' => 'checkbox-inline']);
            ?>
        </div>
        <div class="form-group">
            <div class="radio">
                <?php
                HTML::inputRadio('inputRadioName1', 'radio HTML::class - text');
                ?>
            </div>
        </div>
        <div class="form-group">
            <?php
            HTML::inputRadio('inputRadioName', 'radio HTML::class - text', 'textvalue01', ['labelClass' => 'radio-inline']);
            ?>
            <?php
            HTML::inputRadio('inputRadioName', 'radio HTML::class - text', 'textvalue02', ['labelClass' => 'radio-inline']);
            ?>
            <?php
            HTML::inputRadio('inputRadioName', 'radio HTML::class - text', 'textvalue03', ['labelClass' => 'radio-inline']);
            ?>
        </div>
        <?php
        HTML::buttonSubmit('textButtonSubmit - HTML::class');
        HTML::buttonSubmit('textButtonSubmit 02 - HTML::class', 'buttonNameTest', 'buttonValueTest', ['class' => 'btn btn-danger btn-block']);
        ?>
    </form>
    
    <?php
    //HTML::link('')->action('')->controller('')->param('')->target('')->title()->echo();
    HTML::link([
        'action'     => 'create',
        'controller' => 'post',
        'param'      => '',
        'text'       => 'Crear Post',
        'title'      => 'Crear Post',
        'addParam'   => 'ejemplo=test',
        'class'      => 'btn btn-primary',
        'id'         => 'btnIdTest',
        'addAttr'    => 'data-test=test',
    ]);
    echo '<br/>';
    HTML::linkAction('myTestAction');
    echo '<br/>';
    HTML::linkController('myTestController');
    echo '<br/>';
    HTML::linkRoute('myTestRoute');
    //    HTML::link('action', 'controller', 'param', 'text', [
    //        'title'  => '',
    //        'target' => '',
    //    ]);
    HTML::imageLocal('softn.png', 'imageTesting', ['class' => 'img-responsive']);
    
    $tBody = [
        [
            'row1-col01',
            'row1-col02',
            'row1-col03',
        ],
        [
            'row2-col01',
            'row2-col02',
            'row2-col03',
        ],
        [
            'row3-col01',
            'row3-col02',
            'row3-col03',
        ],
    ];
    $tHead = [
        'head01',
        'head02',
        'head03',
    ];
    $tFoot = [
        'foot01',
        'foot02',
        'foot03',
    ];
    ?>
    <div class="table-responsive">
    <?php HTML::table(HTML::createTableValue($tBody, $tHead, $tFoot), [], [], ['class' => 'table table-striped']); ?>
    </div>
    <div class="table-responsive">
    <?php
    $columnData = [
        0 => [
            'class'            => 'columnAllClass',
            'data-all-columns' => 'testingColumns',
        ],
        2 => [
            'class'          => 'column02Class',
            'data-column-02' => 'testingColumn02',
        ],
    ];
    $rowData    = [
        0 => [
            'class'        => 'rowAllClass',
            'data-all-row' => 'testingRow',
        ],
        2 => [
            'class'       => 'row02Class',
            'data-row-02' => 'testingRow02',
        ],
    ];
    $columnDataHeadFoot = [
      0 => [
          'class' => 'columnHeadFootAllClass',
          'data-all-head-foot' => 'columnDataHeadFootAllClass',
      ],
      2 => [
          'class' => 'column02Testing',
          'data-testing-02' => 'column02DataTesting',
      ]
    ];
    $rowDataHeadFoot = [
      0 => [
          'class' => 'rowHeadFootAllClass',
          'data-all-head-foot' => 'rowDataHeadFootAllClass',
      ],
      2 => [
          'class' => 'row02Testing',
          'data-testing-02' => 'row02DataTesting',
      ]
    ];
    HTML::table(HTML::createTableValue($tBody, $tHead, $tFoot), $columnData, $rowData, [
        'class'              => 'table table-striped',
        'columnDataHeadFoot' => $columnDataHeadFoot,
        'rowDataHeadFoot'    => $rowDataHeadFoot,
    ]);
    echo '<p>tfoot igual que thead</p>';
    HTML::table(HTML::createTableValue($tBody, $tHead, null), $columnData, $rowData, [
        'class'              => 'table table-striped',
        'columnDataHeadFoot' => $columnDataHeadFoot,
        'rowDataHeadFoot'    => $rowDataHeadFoot,
    ]); ?>
    </div>

</div>
