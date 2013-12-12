; <?php die('No Access');
[0]
api_period        = day                                       ; day, week, month or year
api_date          = last14                                    ; lastX, previousX
width             = 745                                       ; width in px
columns           = nb_visits                                 ; nb_uniq_visitors,nb_visits,nb_actions (No spaces!)

[1]
api_period        = month
api_date          = last4
width             = 402
columns           = nb_visits,nb_actions

[2]
; widget_title      = This and last year
api_period        = year
api_date          = last2
width             = 322
columns           = nb_visits,nb_actions