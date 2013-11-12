Elcweb CommonBundle
======================

To store all DateTime as UTC DateTime add this to config.yml

```
doctrine:
    dbal:
        types:
            datetime: Elcweb\CommonBundle\DBAL\Types\UTCDateTimeType
```

