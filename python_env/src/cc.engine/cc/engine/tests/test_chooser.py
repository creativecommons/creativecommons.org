def test_issue_license_with_license_code():
    """Test cc.engine.chooser.views_issue_license by specifying a
    license code."""

    import cc.engine.chooser.views as views
    from webob import Request

    # request for CC BY Unported, latest version
    request = Request.blank('/results-one')
    request.GET.add('license_code', 'by')

    assert views._issue_license(request.GET).uri == 'http://creativecommons.org/licenses/by/3.0/'

    # request for CC BY Unported, specific version
    request = Request.blank('/results-one')
    request.GET.add('license_code', 'by')
    request.GET.add('version', '2.0')

    assert views._issue_license(request.GET).uri == 'http://creativecommons.org/licenses/by/2.0/'

    # request for CC BY Australia
    request = Request.blank('/results-one')
    request.GET.add('license_code', 'by')
    request.GET.add('field_jurisdiction', 'au')

    assert views._issue_license(request.GET).uri == 'http://creativecommons.org/licenses/by/3.0/au/'

    # request for CC BY Australia using "jurisdiction" as the query string param
    request = Request.blank('/results-one')
    request.GET.add('license_code', 'by')
    request.GET.add('jurisdiction', 'au')

    assert views._issue_license(request.GET).uri == 'http://creativecommons.org/licenses/by/3.0/au/'

    
